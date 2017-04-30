@extends('layouts.dashboard')

@section('title', !empty($postRequest) ? ' - Edit: ' . $postRequest->title : ' - New Post Request')

@section('css')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
@endsection

@section('content')
    <form class="form-horizontal"
          action="{{ url('dashboard/post/request' . (!empty($postRequest) ? '/' . $postRequest->id : '')) }}"
          method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-6">
                <a href="{{url('dashboard/post/request/list')}}" class="btn bg-indigo-400 btn-labeled btn-rounded"><b><i
                                class="glyphicon glyphicon-chevron-left"></i></b> All Post Requests</a><br><br>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- Basic layout-->

                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Post Request Details</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse" class=""></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-lg-1 control-label">Post Request Title:</label>

                            <div class="col-lg-9">
                                <input name="title" type="text" class="form-control"
                                       placeholder="Enter a title for this post request..." value="{{$postRequest->title or ''}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-1 control-label">Description (tagline):</label>

                            <div class="col-lg-9">
                                <textarea name="description" rows="4" class="form-control">{{$postRequest->description or ''}}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-1 control-label">Price Per Post ($):</label>

                            <div class="col-lg-9">
                                <input name="price_per_post" type="text" class="form-control"
                                       placeholder="Enter a price for this post..." value="{{$postRequest->price_per_post or ''}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-1 control-label">Recurring:</label>

                            <div class="col-lg-9">
                                <input name="recurring" type="checkbox" class="form-control" {{ $postRequest && $postRequest->recurring ? 'checked="checked"' : '' }}/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-1 control-label">Status:</label>

                            <div class="col-lg-9">
                                <select id="status" name="status" class="form-control select">
                                    <option value="0" {{ !empty($postRequest) && $postRequest->status == 0 ? 'selected' : '' }}>
                                        Request Created
                                    </option>
                                    <option value="1" {{ !empty($postRequest) && $postRequest->status == 1 ? 'selected' : '' }}>
                                        Request Fulfilled
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="text-right">
                            @if(!empty($post))
                                <a class="btn btn-success legitRipple" href="{{url($postRequest->slug)}}?__preview=1"
                                   target="_blank">Preview Post<i
                                            class="icon-arrow-right14 position-right"></i></a>
                            @endif
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