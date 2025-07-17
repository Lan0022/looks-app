<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LOOKS - Modern Fashion Retail')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

<body x-data="{ mobileMenuOpen: false, quickViewOpen: false, quickViewProduct: {} }">
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.quick-view-modal')

    @stack('scripts')
</body>

</html>
