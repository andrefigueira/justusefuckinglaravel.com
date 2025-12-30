<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-6GM7JT9KS8"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-6GM7JT9KS8');
        </script>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Stop overcomplicating it. You don't need 47 microservices or that hip new thing on Hacker News. You need to ship. Laravel ships.">

        <title inertia>Just Use Fucking Laravel</title>

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://justusefuckinglaravel.com/">
        <meta property="og:title" content="Just Use Fucking Laravel">
        <meta property="og:description" content="Stop overcomplicating it. You don't need 47 microservices or that hip new thing on Hacker News. You need to ship. Laravel ships.">
        <meta property="og:image" content="https://justusefuckinglaravel.com/og-image.png">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="https://justusefuckinglaravel.com/">
        <meta name="twitter:title" content="Just Use Fucking Laravel">
        <meta name="twitter:description" content="Stop overcomplicating it. You don't need 47 microservices or that hip new thing on Hacker News. You need to ship. Laravel ships.">
        <meta name="twitter:image" content="https://justusefuckinglaravel.com/og-image.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|space-grotesk:500,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="antialiased">
        @inertia
    </body>
</html>
