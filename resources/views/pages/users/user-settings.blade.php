@extends('layouts.dashboard')

@section('title', ' - Settings')

@section('css')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
@endsection

@section('content')


    @if(!empty($message))
        <div class="alert alert-{{explode('|', $message)[0]}} alert-styled-left">
            {{explode('|', $message)[1]}}
        </div>
    @endif

    <form class="form-horizontal"
          action="{{ url('dashboard/user/settings') }}"
          method="post"
          enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">User Details</h5>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Display Name:</label>

                            <div class="col-lg-9">
                                <input name="name" type="text" class="form-control"
                                       placeholder="Enter a display name..."
                                       value="{{$user->name or ''}}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">E-mail:</label>

                            <div class="col-lg-9">
                                <input name="email" type="text" class="form-control"
                                       placeholder="Enter email address..."
                                       value="{{$user->email or ''}}" required email>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Display Image:</label>

                            <div class="col-lg-1">
                                <img style="width:100px;height:100px" src="{{$user->image or ''}}"/>
                            </div>
                            <div class="col-lg-6">
                                <input name="image" type="file" class="form-control">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary legitRipple">Save Settings<i
                                        class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form class="form-horizontal"
          action="{{ url('dashboard/user/password') }}"
          method="post"
          enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Password</h5>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Current Password:</label>

                            <div class="col-lg-9">
                                <input name="password" type="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">New Password:</label>

                            <div class="col-lg-9">
                                <input name="new_password" type="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">New Password Confirmation:</label>

                            <div class="col-lg-9">
                                <input name="new_password_confirmation" type="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary legitRipple">Change Password<i
                                        class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Statistics</h5>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Posts This Month:</label>

                        <label class="col-lg-3 control-label">{{ $postsThisMonth }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-bottom')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.5.0/fabric.min.js"></script>
@endsection