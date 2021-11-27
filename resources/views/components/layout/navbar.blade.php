<header class="bg-white grid grid-cols-5">
    <div class="col-span-1 bg-gray-900 py-3 flex items-center justify-around">
        <a href="/" class="text-white hover:text-gray-500 text-xl">
            {{ config('invfija.app_nombre') }}
        </a>
    </div>

    <div class="col-span-4 flex justify-between items-center border-b px-8">
        <div class="text-lg">
            {{ auth()->user()->moduloAppName(request()) }}
        </div>

        <div x-data="{ openMenu: false }">
            <button
                x-on:click="openMenu = !openMenu"
                class="flex items-center rounded-md border border-white hover:bg-gray-100 hover:border-gray-400 px-2 py-1"
            >
                <img src="{{ auth()->user()->avatarLink() }}" class="block rounded-full border" />
                <x-heroicon.chevron-down />
            </button>

            <ul
                class="absolute right-0 mr-6 bg-white py-2 border rounded-lg shadow-lg"
                style="display:none"
                x-show="openMenu"
                x-transition
                @click.outside="openMenu = false"
            >
                <li class="px-4 py-1">
                    Signed in as {{ auth()->user()->getfirstname() }}
                </li>
                <li class="px-4 py-1">
                    <hr>
                </li>
                <li class="px-4 py-1 hover:bg-gray-100">
                    <a class="flex items-center" href="#" @click.prevent="$refs.logoutform.submit()">
                        <span class="fa fa-power-off fa-fw"></span>
                        <div class="px-2">
                            Logout
                        </div>
                    </a>
                    <form method="POST" action=" {{ route('logout') }}" x-ref="logoutform">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
