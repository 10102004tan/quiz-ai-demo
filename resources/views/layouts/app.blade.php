<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    @vite('resources/css/app.css')
</head>

<body>
    <header>
        Header
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        Footer
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @yield('script')
</body>

</html>