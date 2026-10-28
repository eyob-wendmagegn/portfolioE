<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>New Portfolio Contact Message</title>
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      background-color: #0b1120;
      color: #f1f5f9;
      margin: 0;
      padding: 24px;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #0f172a;
      border: 1px solid #1e293b;
      border-radius: 16px;
      padding: 32px;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
    }
    .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 9999px;
      background-color: rgba(59, 130, 246, 0.15);
      border: 1px solid rgba(59, 130, 246, 0.3);
      color: #60a5fa;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 12px;
    }
    h1 {
      font-size: 20px;
      font-weight: 800;
      color: #ffffff;
      margin: 0 0 16px 0;
    }
    .detail-card {
      background-color: #080d1a;
      border: 1px solid #1e293b;
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 20px;
    }
    .detail-row {
      display: flex;
      padding: 6px 0;
      font-size: 13px;
      border-bottom: 1px solid #1e293b;
    }
    .detail-row:last-child {
      border-bottom: none;
    }
    .label {
      width: 110px;
      color: #94a3b8;
      font-weight: 600;
    }
    .val {
      color: #f8fafc;
      font-weight: 500;
    }
    .message-box {
      background-color: #080d1a;
      border: 1px solid #334155;
      border-radius: 12px;
      padding: 20px;
      color: #e2e8f0;
      font-size: 14px;
      line-height: 1.6;
      white-space: pre-line;
      margin: 20px 0;
    }
    .btn {
      display: inline-block;
      padding: 12px 24px;
      background: linear-gradient(135deg, #2563eb, #4f46e5);
      color: #ffffff !important;
      text-decoration: none;
      font-weight: 700;
      font-size: 13px;
      border-radius: 10px;
      text-align: center;
      margin-top: 10px;
    }
    .footer {
      margin-top: 28px;
      padding-top: 16px;
      border-top: 1px solid #1e293b;
      font-size: 11px;
      color: #64748b;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="badge">🔔 Real-Time Notification</div>
    <h1>New Contact Message Received</h1>
    
    <div class="detail-card">
      <div class="detail-row">
        <span class="label">Sender:</span>
        <span class="val">{{ $contactMessage->name }}</span>
      </div>
      <div class="detail-row">
        <span class="label">Email:</span>
        <span class="val"><a href="mailto:{{ $contactMessage->email }}" style="color: #60a5fa; text-decoration: none;">{{ $contactMessage->email }}</a></span>
      </div>
      <div class="detail-row">
        <span class="label">Subject:</span>
        <span class="val">{{ $contactMessage->subject }}</span>
      </div>
      @if($contactMessage->project_type)
      <div class="detail-row">
        <span class="label">Project Type:</span>
        <span class="val">{{ $contactMessage->project_type }}</span>
      </div>
      @endif
      <div class="detail-row">
        <span class="label">Time:</span>
        <span class="val">{{ $contactMessage->created_at->format('M d, Y h:i A') }}</span>
      </div>
    </div>

    <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Message Content:</div>
    <div class="message-box">
{{ $contactMessage->message }}
    </div>

    <div style="text-align: center;">
      <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ rawurlencode($contactMessage->subject) }}" class="btn">
        Reply Directly to {{ $contactMessage->name }}
      </a>
    </div>

    <div class="footer">
      Sent from your portfolio website: Eyob.dev &bull; Eyob Wendmamagegn
    </div>
  </div>
</body>
</html>
