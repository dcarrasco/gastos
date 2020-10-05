<!doctype html>
<html lang="es">
    <x-layout.head />

    <body class="bg-gray-200" style="font-family: Nunito">

        <x-layout.navbar />

        <div class="grid grid-cols-5 h-screen">
            @guest
                <div class="col-span-5">
                    {{ $slot }}
                </div>
            @else
                <div class="col-span-1 bg-gray-700 h-full">
                    <x-layout.menu-modulo />
                </div>

                <div class="col-span-4 px-12 py-10 text-gray-700">
                    <x-alert :errors=$errors />
                    {{ $slot }}
                    <x-layout.footer />
                </div>
            @endguest
        </div> <!-- DIV principal de la aplicacion   class="container"-->

    </body>
</html>
