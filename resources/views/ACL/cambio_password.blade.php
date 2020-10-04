<x-layout.app>

    <div class="grid grid-cols-3 text-gray-700">

        <div class="col-start-2 bg-white rounded-lg py-1 grid grid-cols-5">
            <div class="col-start-2 col-span-3">
                <h2 class="text-center text-3xl p-2">{{ trans('login.form_change_password') }}</h2>
                <hr>

                <div class="col-md-12">
                    <x-alert :errors=$errors />
                </div>

                <form method="POST" id="frm_login" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="username" value="{{ request('username') }}">

                    @if ($userHasPassword)
                        <label class="block py-2" for="clave_anterior">{{ trans('login.input_old_password') }}</label>
                        <input type="password" name="clave_anterior" class="p-2 border outline-none focus:shadow-outline rounded-md w-full shadow-xs" maxlength="45" autocomplete="off">
                    @endif

                    <label class="block py-2" for="nueva_clave">{{ trans('login.input_new1_password') }}</label>
                    <input type="password" name="nueva_clave" class="p-2 border outline-none focus:shadow-outline rounded-md w-full shadow-xs" maxlength="45" autocomplete="off">
                    <em><small>M&iacute;nimo 8 caracteres. Debe incluir may&uacute;sculas, min&uacute;sculas y n&uacute;meros.</small></em>

                    <label class="block py-2" for="nueva_clave_confirmation">{{ trans('login.input_new2_password') }}</label>
                    <input type="password" name="nueva_clave_confirmation" class="mb-4 p-2 border outline-none focus:shadow-outline rounded-md w-full shadow-xs" maxlength="45" autocomplete="off">

                    <hr/>

                    <button type="submit" name="btn_submit" class="my-4 w-full bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-md outline-none">
                        <span class="fa fa-lock"></span> {{ trans('login.button_change_password') }}
                    </button>
                </form>
            </div>
        </div>

    </div>

</x-layout.app>
