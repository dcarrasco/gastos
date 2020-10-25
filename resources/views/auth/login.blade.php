<x-layout.app>

    <div class="flex flex-col justify-center h-screen bg-cover" style="background-image: url('{{ asset('img/tch-background.jpg') }}')">
        <div class="grid grid-cols-3">
            <div class="col-start-2 bg-white rounded-lg grid grid-cols-5 h-auto">
                <div
                    class="col-start-2 col-span-3"
                    x-data="{ username: '', usernameError: false, url: '{{ route("acl.cambiaPassword", [""]) }}' }"
                >
                    <h2 class="text-center text-3xl p-2">{{ trans('login.form_title') }}</h2>
                    <hr>
                    <div class="col-md-12">
                        <x-alert :errors=$errors />
                    </div>

                    <form method="POST" id="form_login">
                        @csrf
                        <label
                            for="username"
                            class="block py-2"
                            :class="{'text-red-400': usernameError}"
                        >
                            {{ trans('login.input_user') }}
                        </label>
                        <input
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            maxlength="45"
                            class="p-2 outline-none focus:shadow-outline rounded-md w-full shadow-xs"
                            tabindex="1"
                            autofocus="autofocus"
                            x-model="username"
                            :class="{ 'border': ! usernameError, 'border-red-400 border-2': usernameError }"
                        >

                        <label for="pwd" class="block py-2">
                            {{ trans('login.input_password') }}
                        </label>
                        <input type="password" name="password" maxlength="45" size="40" tabindex="2" class="p-2 border outline-none focus:shadow-outline rounded-md w-full shadow-xs" autocomplete="off">

                        <div class="flex justify-between py-4 text-sm">
                            <div class="">
                                <input type="checkbox" name="remember" value="1" class="border" id="remember-id">
                                <label class="custom-control-label" for="remember-id">
                                    {{ trans('login.check_remember_me') }}
                                </label>
                            </div>

                            <a href="#"
                                class="hover:text-blue-500"
                                @click.prevent="if (username.trim() == '') {
                                    usernameError = true;
                                    alert('Debe ingresa un nombre de usuario para cambiarle la clave');
                                } else {
                                    window.location.href = url+'/'+username;
                                }"
                            >{{ trans('login.link_change_password') }}</a>
                        </div>

                        <hr>

                        <x-button color="green" type="submit" name="btn_submit" class="text-lg w-full my-4">
                            {{ trans('login.button_login') }} &nbsp; <span class="fa fa-sign-in"></span>
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
        <x-layout.footer />
    </div>

</x-layout.app>
