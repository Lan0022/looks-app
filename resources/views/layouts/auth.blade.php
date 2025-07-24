<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Looks - Modern E-commerce')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    <style>
        body {
            font-family: "Open Sans", sans-serif;
            background-color: #f8f8f8;
            color: #222222;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-heading {
            font-family: "Montserrat", sans-serif;
        }

        .bg-accent {
            background-color: #556b2f;
        }

        .text-accent {
            color: #556b2f;
        }

        .border-accent {
            border-color: #556b2f;
        }

        .hover\:bg-accent-dark:hover {
            background-color: #4a5d28;
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased">
    <div id="app" class="min-h-screen bg-gray-50">
        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
