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
            <form action="{{url('password-reset')}}" method="POST">
                <div class="panel panel-body login-form">
                    <div class="text-center">
                        <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
                        <h5 class="content-group">Password recovery <small class="display-block">We'll send you instructions in email</small></h5>
                    </div>

                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" placeholder="Your email">
                        <div class="form-control-feedback">
                            <i class="icon-mail5 text-muted"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn bg-blue btn-block">Reset password <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </form>
@endsection