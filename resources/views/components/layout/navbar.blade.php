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

        <div class="">
            <a class="flex items-center" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <span class="fa fa-power-off fa-fw"></span>
                <div class="px-2">
                    Logout {{ auth()->user()->getFirstName() }}
                </div>
                <img src="{{ auth()->user()->avatarLink() }}" class="block rounded-full border" />
            </a>
            <form method="POST" action=" {{ route('logout') }}" id="logout-form">
                @csrf
            </form>
        </div>
    </div>
</header>
