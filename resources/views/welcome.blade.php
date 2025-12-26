<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>

    <meta name="theme-color" content="#2563eb"/>

    <title>{{ config('app.name') }}</title>
    <meta
        name="description"
        content="Mini Wallet is a secure digital wallet demo showcasing real-time transactions, atomic balance handling and modern web architecture."
    />
    <meta name="author" content="Jitendra Patel"/>
    <link rel="canonical" href="{{ url('/') }}"/>

    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta
        property="og:description"
        content="Mini Wallet — a modern digital wallet demo built with Laravel and Vue."
    />
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ url('/') }}"/>

    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:title" content="{{ config('app.name') }}"/>
    <meta
        name="twitter:description"
        content="Mini Wallet — real-time digital wallet demo."
    />

    <link rel="manifest" href="/manifest.json"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="default"/>
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}"/>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>
<body>
<div id="app"></div>
</body>
</html>
