{{-- resources/views/errors/layout.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#FCF8F7] flex items-center justify-center min-h-screen text-center">
    @yield('content')
</body>
</html>