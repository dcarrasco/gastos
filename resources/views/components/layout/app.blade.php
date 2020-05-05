<!doctype html>
<html lang="es">
    <x-layout.head />

    <body>
        <x-layout.navbar />

        <div class="container-fluid" id="container">
            <div class="row vh-100">
                @guest
                    <div class="col-12">
                        {{ $slot }}
                    </div>
                @else
                    <div class="col-2 bg-secondary px-0">
                        <x-layout.menu-modulo />
                    </div>

                    <div class="col-10 px-5 pt-5 pb-2">
                        <x-alert :errors=$errors />
                        {{ $slot }}
                        <x-layout.footer />
                    </div>
                @endguest
            </div> <!-- DIV   class="row"    -->
        </div> <!-- DIV principal de la aplicacion   class="container"-->

    </body>
</html>
