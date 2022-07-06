<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .alert-danger {
                color: red;
            }

            .alert {
                position: relative;
                margin-bottom: 1rem;
                border: 1px solid transparent;
                border-radius: 0.25rem;
            }
            .is-invalid {
                border-color: red;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script>
            function onDelete(obj)
            {
                if (confirm('Are you sure to delete this?')) {
                    const xhttp = new XMLHttpRequest();
                    xhttp.onload = function() {
                        let response = JSON.parse(this.responseText);
                        if (response.success) {
                            window.location = response.url;
                        } else {
                            window.location.reload();
                        }
                    }
                    xhttp.open("POST", obj.getAttribute('data-url'));
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    let token =document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    xhttp.send("_method=DELETE&_token=" + token);
                }
            }
        </script>
    </body>
</html>
