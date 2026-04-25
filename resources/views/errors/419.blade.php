<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 — Session Expired</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif; min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #EED7C8 0%, #FFF9F5 60%, #EFAAB0 100%);
        }
        .card {
            text-align: center; padding: 56px 48px; max-width: 440px; width: 90%;
            background: rgba(255,255,255,0.52); backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.72); border-radius: 28px;
            box-shadow: 0 12px 48px rgba(190,8,34,0.12);
        }
        .code  { font-size: 5rem; font-weight: 800; color: #BE0822; line-height: 1; margin-bottom: 8px; }
        .title { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-style: italic; color: #3d1a22; margin-bottom: 12px; }
        .desc  { font-size: 0.88rem; color: rgba(107,34,50,0.60); margin-bottom: 28px; line-height: 1.6; }
        .btn   {
            display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px;
            border-radius: 14px; background: linear-gradient(135deg, #BE0822, #E86975);
            color: white; font-size: 0.88rem; font-weight: 600; text-decoration: none;
            font-family: inherit; box-shadow: 0 4px 20px rgba(190,8,34,0.32);
            transition: transform 0.2s;
        }
        .btn:hover { transform: translateY(-2px); }
        .brand { font-family: 'Playfair Display', serif; font-style: italic; font-size: 0.85rem; color: rgba(190,8,34,0.25); margin-top: 24px; }
    </style>
</head>
<body>
    <div class="card">
        <div style="font-size:3rem;margin-bottom:12px;">⏱️</div>
        <div class="code">419</div>
        <div class="title">Session Expired</div>
        <div class="desc">Your session has expired for security. Please refresh the page and try again.</div>
        <a href="{{ url()->previous() }}" class="btn" onclick="event.preventDefault(); history.back();">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4"/></svg>
            Refresh & Try Again
        </a>
        <div class="brand">heartstrings</div>
    </div>
</body>
</html>
