{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PT. Kupang Tour & Travel' }}</title>

    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    {{-- Bootstrap CSS via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Custom App CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">


</head>
<body>
    <x-navbar />

    <main class="container my-4">
        {{ $slot }}
    </main>

    {{-- Bootstrap JS (buat dropdown, hamburger menu, dsb) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
