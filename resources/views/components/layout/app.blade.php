@props(['resource' => null, 'accion' => null])
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
                    <div class="col-span-1 {{ themeColor('menu_bg') }}">
                        <x-layout.menu-modulo :modulos="auth()->user()->getMenuApp(request())" :menuModulo="$menuModulo ?? []" />
                    </div>
                    <div class="col-span-4 px-12 py-10">

                        <!-- ---------------------- BREADCRUMBS ---------------------- -->
                        <x-orm.breadcrumbs :resource=$resource :accion=$accion/>

                        <!-- ------------------------ ERRORS ------------------------- -->
                        <x-alert :errors=$errors />

                        <!-- ------------------------ CONTENT ------------------------ -->
                        {{ $slot }}

                        <x-layout.footer />
                    </div>
                </div>
            </div>
        @endguest
    </body>

</html>
