@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>B2B | Password Reset</title>

         
    </head>

    <body class="login">
        <div>
            <a class="hiddenanchor" id="signup"></a>
            <a class="hiddenanchor" id="lostpassword"></a>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">      
                    <section class="login_content">
                        @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif
                        <form role="form" method="POST" action="{{ route('password.reset') }}">
                            <h4>Reset Password</h4>

                            <br>
                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                              <label for="email" class="col-md-4 control-label">E-mail</label>
                              <div class="col-md-6">  
                                @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                @endif
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email"/>
                              </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                              <label for="password" class="col-md-4 control-label">Password</label>

                               <div class="col-md-6">
                                @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                                @endif
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password"/>
                               </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                              
                              <label for="password_confirmation" class="col-md-4 control-label">Password Confirmation</label>

                              <div class="col-md-6">
                                @if ($errors->has('password_confirmation'))
                                <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                                @endif
                                <input type="password_confirmation" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"/>
                              </div>  
                            </div>

                            <br>
                            <div class="clearfix"></div>

                            <div class="separator">

                            <div class="clearfix"></div>
                            
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Reset Password
                                    </button>
                                </div>
                            </div>
                                                       

                            
                                
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>
@endsection
