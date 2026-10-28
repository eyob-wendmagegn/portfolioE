<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Response from Eyob Wendmamagegn</title>
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
    .logo-badge {
      display: inline-block;
      padding: 6px 14px;
      border-radius: 8px;
      background: linear-gradient(135deg, #2563eb, #4f46e5);
      color: #ffffff;
      font-size: 13px;
      font-weight: 800;
      letter-spacing: -0.02em;
      margin-bottom: 20px;
    }
    h2 {
      font-size: 18px;
      font-weight: 700;
      color: #ffffff;
      margin: 0 0 16px 0;
    }
    .reply-body {
      color: #f8fafc;
      font-size: 15px;
      line-height: 1.7;
      white-space: pre-line;
      margin-bottom: 28px;
    }
    .quote-box {
      background-color: #080d1a;
      border-left: 3px solid #3b82f6;
      border-radius: 4px 8px 8px 4px;
      padding: 16px;
      color: #94a3b8;
      font-size: 13px;
      line-height: 1.5;
      white-space: pre-line;
    }
    .footer {
      margin-top: 32px;
      padding-top: 18px;
      border-top: 1px solid #1e293b;
      font-size: 12px;
      color: #64748b;
      line-height: 1.5;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-badge">Eyob.dev</div>
    <h2>Hi {{ $contactMessage->name }},</h2>
    
    <div class="reply-body">
{{ $replyText }}
    </div>

    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">
      --- In reply to your inquiry regarding "{{ $contactMessage->subject }}" ---
    </div>
    <div class="quote-box">
{{ $contactMessage->message }}
    </div>

    <div class="footer">
      <strong>Eyob Wendmamagegn</strong><br>
      Full-Stack Developer &bull; Laravel & Vue.js Specialist<br>
      <a href="https://github.com/eyob-wendmagegn" style="color: #60a5fa; text-decoration: none;">GitHub</a> &bull; 
      <a href="https://linkedin.com/in/eyob-w" style="color: #60a5fa; text-decoration: none;">LinkedIn</a> &bull; 
      <a href="mailto:eyobnigata18@gmail.com" style="color: #60a5fa; text-decoration: none;">eyobnigata18@gmail.com</a>
    </div>
  </div>
</body>
</html>
