<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Forbidden</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700&family=Playfair+Display:ital@0;1&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #E86975 0%, #EED7C8 50%, #FFF9F5 100%);
        }
        .card {
            text-align: center; padding: 56px 48px;
            background: rgba(255,255,255,0.42);
            backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.60);
            border-radius: 28px;
            box-shadow: 0 12px 48px rgba(190,8,34,0.14);
            max-width: 440px; width: 90%;
        }
        .code { font-size: 5rem; font-weight: 800; color: #BE0822; line-height: 1; margin-bottom: 8px; }
        .title { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-style: italic; color: #3d1a22; margin-bottom: 12px; }
        .desc { font-size: 0.88rem; color: rgba(107,34,50,0.60); margin-bottom: 28px; line-height: 1.6; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 28px; border-radius: 14px;
            background: linear-gradient(135deg, #BE0822, #E86975);
            color: white; font-size: 0.88rem; font-weight: 600;
            text-decoration: none; font-family: inherit;
            box-shadow: 0 4px 20px rgba(190,8,34,0.32);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(190,8,34,0.44); }
        .brand { font-family: 'Playfair Display', serif; font-style: italic; font-size: 0.85rem; color: rgba(190,8,34,0.30); margin-top: 24px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="code">403</div>
        <div class="title">Access Forbidden</div>
        <div class="desc">You don't have permission to access this page. Please contact your administrator if you believe this is an error.</div>
        <a href="{{ url('/') }}" class="btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Back to Home
        </a>
        <div class="brand">heartstrings</div>
    </div>
</body>
</html>
