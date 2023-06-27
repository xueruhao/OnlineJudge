@extends('layouts.client')

@section('title', __('main.Register'))

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">{{ __('main.Register') }}</div>

          <div class="card-body">
            @if (get_setting('allow_register'))
              <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group row">
                  <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('main.Username') }}</label>

                  <div class="col-md-6">
                    <input id="name" type="text" class="form-control @error('username') is-invalid @enderror"
                      name="username" value="{{ old('username') }}"
                      oninput="this.value=this.value.replace(/[^a-zA-Z0-9]/g,'')" required autofocus
                      placeholder="{{ __('sentence.Must fill') }}" maxlength="30" minlength="4">

                    @error('username')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('main.Password') }}</label>

                  <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                      name="password" required autocomplete="new-password" maxlength="30"
                      oninput="this.value=this.value.replace(/\s+/g,'')">

                    <span class="position-absolute" style="top:0.5rem;right:2rem; cursor: pointer;"
                      onclick="toggle_password()">
                      <i id="eye-show-password" class="fa fa-lg fa-eye-slash" aria-hidden="true"></i>
                    </span>

                    <script>
                      function toggle_password() {
                        if ($("#eye-show-password").is('.fa-eye-slash')) {
                          $("#eye-show-password").removeClass('fa-eye-slash')
                          $("#eye-show-password").addClass('fa-eye')
                          $("#password").attr("type", "")
                          $("#password-confirm").attr("type", "")
                        } else {
                          $("#eye-show-password").removeClass('fa-eye')
                          $("#eye-show-password").addClass('fa-eye-slash')
                          $("#password").attr("type", "password")
                          $("#password-confirm").attr("type", "password")
                        }
                      }
                    </script>

                    @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label for="password-confirm"
                    class="col-md-4 col-form-label text-md-right">{{ __('main.Confirm Password') }}</label>

                  <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                      required autocomplete="new-password" oninput="this.value=this.value.replace(/\s+/g,'')">
                  </div>
                </div>


                <div class="form-group row">
                  <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('main.E-Mail') }}</label>

                  <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                      name="email" value="{{ old('email') }}" autocomplete="email"
                      placeholder="{{ trans('sentence.Non essential') }}"
                      oninput="this.value=this.value.replace(/\s+/g,'')">

                    @error('email')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label for="school" class="col-md-4 col-form-label text-md-right">{{ __('main.School') }}</label>

                  <div class="col-md-6">
                    <input id="school" type="text" class="form-control" name="school" value="{{ old('school') }}">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="class" class="col-md-4 col-form-label text-md-right">{{ __('main.Class') }}</label>

                  <div class="col-md-6">
                    <input id="class" type="text" class="form-control" name="class" value="{{ old('class') }}">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="nick" class="col-md-4 col-form-label text-md-right">{{ __('main.Name') }}</label>

                  <div class="col-md-6">
                    <input id="nick" type="text" class="form-control" name="nick" value="{{ old('nick') }}">
                  </div>
                </div>

                @if (get_setting('login_reg_captcha'))
                  <div class="form-group row">
                    <label for="captcha" class="col-md-4 col-form-label text-md-right">验证码</label>
                    <div class="col-md-6">
                      <input id="captcha" class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}"
                        name="captcha" required oninput="this.value=this.value.replace(/\s+/g,'')">
                      <img class="thumbnail mt-3 mb-2" src="{{ captcha_src() }}"
                        onclick="this.src='/captcha?'+Math.random()" title="点击图片重新获取验证码">
                      @if ($errors->has('captcha'))
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('captcha') }}</strong>
                        </span>
                      @endif
                    </div>
                  </div>
                @endif

                <div class="form-group row mb-0">
                  <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                      {{ __('main.Register') }}
                    </button>
                  </div>
                </div>
              </form>
            @else
              <p>{{ __('sentence.Not_allow_register') }}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
