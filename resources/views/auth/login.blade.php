<x-layout.app>

    <div class="row">

        <div class="col-md-4 offset-md-4 card">
            <div class="card-body">
                <div class="control-group col-md-10 offset-md-1 col-xs-12">
                    <h2 class="text-center">{{ trans('login.form_title') }}</h2>
                </div>

                <div class="control-group col-md-10 offset-md-1 col-xs-12">
                    <hr>
                </div>

                <div class="col-md-12">
                    <x-alert :errors=$errors />
                </div>

                <form method="POST" id="form_login">
                    @csrf
                    <div class="form-group col-md-10 offset-md-1 col-xs-12 {{ $errors->has('username') ? 'has-error' : '' }}">
                        <label for="username">
                            {{ trans('login.input_user') }}
                        </label>
                        <input type="text" name="username" value="{{ old('username') }}" maxlength="45" class="form-control form-control-lg" tabindex="1" autofocus="autofocus">
                    </div>

                    <div class="form-group col-md-10 offset-md-1 col-xs-12 {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label for="pwd">
                            {{ trans('login.input_password') }}
                        </label>
                        <input type="password" name="password" maxlength="45" size="40" tabindex="2" class="form-control form-control-lg" autocomplete="off">
                    </div>

                    <div class="control-group col-md-10 offset-md-1 col-xs-12">
                        <div class="pull-right">
                            <a href="#" id="link_cambia_password">{{ trans('login.link_change_password') }}</a>
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox col-md-10 offset-md-2">
                        <input type="checkbox" name="remember" value="1" class="custom-control-input" id="remember-id">
                        <label class="custom-control-label" for="remember-id">
                            {{ trans('login.check_remember_me') }}
                        </label>
                    </div>

                    <div class="control-group col-md-10 offset-md-1 col-xs-12">
                        <hr>
                    </div>

                    <div class="control-group col-md-10 offset-md-1 col-xs-12">
                        <button type="submit" name="btn_submit" class="btn btn-success btn-lg col-md-12">
                            {{ trans('login.button_login') }} &nbsp; <span class="fa fa-sign-in"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript">
            $( document ).ready(function() {
                $('#link_cambia_password').click(function(e) {
                    e.preventDefault();
                    username = $('#form_login input[name="username"]').val();
                    if (username !== '') {
                        window.location.href = '{{ route("acl.cambiaPassword", [""]) }}/'+username;
                    } else {
                        alert('Debe ingresa un nombre de usuario para cambiarle la clave');
                    }
                });
            });
        </script>
    </div>

</x-layout.app>
