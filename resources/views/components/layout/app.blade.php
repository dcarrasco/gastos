<!doctype html>
<html lang="es">
    <x-layout.head />

    <body class="bg-gray-200" style="font-family: Nunito">
        @guest
            {{ $slot }}
        @else
            <x-layout.navbar />

            <div class="grid grid-cols-5 h-screen">
                <div class="col-span-1 bg-gray-700 h-full">
                    <x-layout.menu-modulo />
                </div>

                <div class="col-span-4 px-12 py-10 text-gray-700">
                    <x-alert :errors=$errors />
                    {{ $slot }}
                    <x-layout.footer />
                </div>
            </div>
        @endguest
    </body>
</html>
