<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>JARA — Collaboration</title>

        <script>
            window.JARA_SESSION_USER = @json([
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ]);
            window.JARA_INITIAL_TAB = @json($tab ?? 'members');
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F0FAFA] text-[#1E293B]">
        <div id="jara-app"></div>

        <noscript>
            <div class="p-6 text-center text-sm text-[#64748B]">
                JARA membutuhkan JavaScript untuk berjalan.
            </div>
        </noscript>
    </body>
</html>