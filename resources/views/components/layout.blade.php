<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
        @vite("resources/css/app.css")
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/basecoat-css@0.3.2/dist/basecoat.cdn.min.css"
        />
        <script
            src="https://cdn.jsdelivr.net/npm/basecoat-css@0.3.2/dist/js/all.min.js"
            defer
        ></script>

        <script>
            if (
                localStorage.getItem('color-theme') === 'dark' ||
                (!('color-theme' in localStorage) &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches)
            ) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            function toggleTheme() {
                const html = document.documentElement;

                if (html.classList.contains('dark')) {
                    html.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        </script>
    </head>
    <body class="bg-gray-100 dark:bg-gray-950 dark:text-white">
        <div class="flex gap-4 h-screen relative">
            <x-sidebar />
            <div class="xl:max-w-4xl mx-auto">
                {{ $slot }}
            </div>
        </div>

        @livewireScripts
    </body>
</html>
