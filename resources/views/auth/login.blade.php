@extends('auth.auth')

@section('content')
<form action="{{url('/login')}}" method="POST">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="panel panel-body login-form">
        <div class="text-center">
            <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
            <h5 class="content-group">Login to your account <small class="display-block">Enter your credentials below</small></h5>
        </div>

        <div class="form-group has-feedback has-feedback-left">
            <input type="text" class="form-control" placeholder="Email" name="email">
            <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
            </div>
        </div>

        <div class="form-group has-feedback has-feedback-left">
            <input type="password" class="form-control" placeholder="Password" name="password">
            <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 position-right"></i></button>
        </div>

        <div class="text-center">
            <a href="{{url('password-reset')}}">Forgot password?</a>
        </div>
    </div>
</form>
@endsection