<!doctype html>
<html lang="es">
    <x-layout.head />

    <body class="bg-gray-200 text-gray-700" style="font-family: Nunito, system-ui, 'Segoe UI', Roboto;">
        @guest
            {{ $slot }}
        @else
            <div class="h-screen flex flex-col">
                <x-layout.navbar />
                <div class="grid grid-cols-5 h-full">
                    <div class="col-span-1 bg-gray-700">
                        <x-layout.menu-modulo />
                    </div>
                    <div class="col-span-4 px-12 py-10">
                        <x-alert :errors=$errors />
                        {{ $slot }}
                        <x-layout.footer />
                    </div>
                </div>
            </div>
        @endguest
    </body>
</html>
