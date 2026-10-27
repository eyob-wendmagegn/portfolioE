<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * List contact messages with filters.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ContactMessage::whereNull('archived_at')
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc');

        if ($request->has('unread') && $request->boolean('unread')) {
            $query->where('is_read', false);
        }

        if ($request->filled('search')) {
            $search = '%' . strtolower($request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(email) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(subject) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(message) LIKE ?', [$search]);
            });
        }

        $messages = $query->paginate($request->input('per_page', 15));

        return response()->json([
            'data' => ContactMessageResource::collection($messages->items()),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
            'unread_count' => ContactMessage::where('is_read', false)->whereNull('archived_at')->count(),
        ]);
    }

    /**
     * Show message and automatically mark as read.
     */
    public function show($id): JsonResponse
    {
        $message = ContactMessage::findOrFail($id);

        if (!$message->is_read) {
            $message->is_read = true;
            $message->save();
        }

        return response()->json([
            'data' => new ContactMessageResource($message),
        ]);
    }

    /**
     * Toggle read/unread status.
     */
    public function toggleRead($id): JsonResponse
    {
        $message = ContactMessage::findOrFail($id);
        $message->is_read = !$message->is_read;
        $message->save();

        return response()->json([
            'message' => $message->is_read ? 'Message marked as read.' : 'Message marked as unread.',
            'data' => new ContactMessageResource($message),
        ]);
    }

    /**
     * Delete contact message.
     */
    public function destroy($id): JsonResponse
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return response()->json([
            'message' => 'Message deleted successfully.'
        ]);
    }
}
