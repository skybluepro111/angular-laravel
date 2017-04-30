@extends('layouts.dashboard')

@section('title', !empty($user) ? ' - Edit: ' . $user->name . ' (' . $user->email . ' )'  : ' - New User')

@section('css')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
@endsection

@section('content')
    <form class="form-horizontal"
          action="{{ url('dashboard/user' . (!empty($user) ? '/' . $user->id : '')) }}"
          method="post"
          enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        @if(!empty($message))
        <div class="alert alert-{{explode('|', $message)[0]}} alert-styled-left">
            {{explode('|', $message)[1]}}
        </div>
        @endif

        <div class="row">
            <a href="{{url('dashboard/user/list')}}" class="btn bg-indigo-400 btn-labeled btn-rounded"><b><i
                            class="glyphicon glyphicon-chevron-left"></i></b> All Users</a><br><br>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- Basic layout-->

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
                                <label class="col-lg-3 control-label">Password:</label>

                                <div class="col-lg-9">
                                    <input name="password" type="password" class="form-control">
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
                                <button type="submit" class="btn btn-primary legitRipple">Save Post<i
                                            class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </div>

                    </div>

                <!-- /basic layout -->
            </div>
        </div>
    </form>
@endsection

@section('js-bottom')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.5.0/fabric.min.js"></script>
@endsection