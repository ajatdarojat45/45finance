<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login - uangKita</title>
        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
        <!-- Styles -->
        {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> {{-- bootstrap --}}
        <link href="{{asset('bootstrap-3/css/bootstrap.min.css')}}" rel="stylesheet"> {{-- dataTables --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
        <link href="{{ asset('inspinia/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="{{ asset('inspinia/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content" >
               <div class="row">
                 <div class="col-lg-12 col-md-12">
                    <div style="border: 1px solid #a1a1a1; margin-top: 15px; padding: 30px;">
                       <h2>uangKita</h2>
                       <p><b>Please login</b> </p>
                       <form method="POST" action="{{ route('login') }}">
                           @csrf

                           <div class="form-group row">
                               <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                               <div class="col-md-8">
                                   <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                   @if ($errors->has('email'))
                                       <span class="invalid-feedback">
                                           <strong>{{ $errors->first('email') }}</strong>
                                       </span>
                                   @endif
                               </div>
                           </div>

                           <div class="form-group row">
                               <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                               <div class="col-md-8">
                                   <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                   @if ($errors->has('password'))
                                       <span class="invalid-feedback">
                                           <strong>{{ $errors->first('password') }}</strong>
                                       </span>
                                   @endif
                               </div>
                           </div>

                           <div class="form-group row">
                               <label for="password" class="col-md-3 col-form-label text-md-right"></label>

                               <div class="col-md-8">
                                  <div class="checkbox">
                                      <label>
                                          <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                                      </label>
                                  </div>
                               </div>
                           </div>

                           <div class="form-group row">
                               <label for="password" class="col-md-2 col-form-label text-md-right"></label>

                               <div class="col-md-8">
                                  <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-sign-in"></i>   {{ __('Login') }}
                                  </button>
                               </div>
                           </div>
                           <b>Or, Login with :</b><br><br>
                           <div class="form-group row">
                               <div class="col-md-3 col-lg-3">
                                  <a href="{{route('auth', 'facebook')}}" class="btn btn-primary btn-social btn-facebook btn-sm">
                                    <span class="fa fa-facebook"></span> Facebook
                                  </a>
                               </div>
                               <div class="col-md-3 col-lg-3">
                                  <a href="{{route('auth', 'twitter')}}" class="btn btn-primary btn-social btn-twitter btn-sm">
                                    <span class="fa fa-twitter"></span> Twitter
                                  </a>
                               </div>
                              <div class="col-md-3 col-lg-3">
                                  <a href="{{route('auth', 'google')}}" class="btn btn-danger btn-social btn-google btn-sm">
                                    <span class="fa fa-google"></span> Google
                                  </a>
                               </div>
                               <div class="col-md-3 col-lg-3">
                                   <a href="{{route('auth', 'github')}}"  style="color:black" class="btn btn-default btn-social btn-google btn-sm">
                                     <span class="fa fa-github"></span> Github
                                   </a>
                                </div>
                           </div>
                           <b>Dont have a account ?</b>
                           <a class="btn btn-link" href="{{ route('register') }}">
                              Register Here
                           </a>
                       </form>
                    </div>
                 </div>
              </div><br>
               <a href="{{route('documentation')}}" target="_blank">View Documentation</a><br>
               <br>
               <b><a href="https://ajatdarojat45.id" target="_blank" style="color:#636b6f;">lazyCode</a> - <i class="fa fa-code"></i> dengan <i class="fa fa-heart" style="color:red"></i> </b>
            </div>
        </div>
    </body>
</html>
