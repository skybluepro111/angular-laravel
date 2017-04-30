@extends('layouts.dashboard')

@section('title', '')
@section('css')

@endsection

@section('content')
    <form action="{{url('dashboard')}}">
        <div class="form-group">
            <label class="col-lg-1 control-label">Select a Month:</label>

            <div class="col-lg-3">
                <select name="reportMonth" class="form-control">
                    <option value="2016-10-01" {{ $reportMonth == '2016-10-01' ? 'selected' : '' }}>2016 - October</option>
                    <option value="2016-11-01" {{ $reportMonth == '2016-11-01' ? 'selected' : '' }}>2016 - November</option>
                    <option value="2016-12-01" {{ $reportMonth == '2016-12-01' ? 'selected' : '' }}>2016 - December</option>
                    <option value="2017-01-01" {{ $reportMonth == '2017-01-01' ? 'selected' : '' }}>2017 - January</option>
                    <option value="2017-02-01" {{ $reportMonth == '2017-02-01' ? 'selected' : '' }}>2017 - February</option>
                    <option value="2017-03-01" {{ $reportMonth == '2017-03-01' ? 'selected' : '' }}>2017 - March</option>
                    <option value="2017-04-01" {{ $reportMonth == '2017-04-01' ? 'selected' : '' }}>2017 - April</option>
                    <option value="2017-05-01" {{ $reportMonth == '2017-05-01' ? 'selected' : '' }}>2017 - May</option>
                    <option value="2017-06-01" {{ $reportMonth == '2017-06-01' ? 'selected' : '' }}>2017 - June</option>
                </select>
            </div>
            <div class="col-lg-3">
                <button type="submit" class="btn btn-primary legitRipple">Show Posts<i
                            class="icon-arrow-right14 position-right"></i></button>
            </div>
        </div>
    </form>
    <br /><br />
    <br />
    @foreach($users as $user)
        @if(!empty($user->posts))
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info panel-bordered">
                        <div class="panel-heading">
                            <h6 class="panel-title">Writing Activity - {{ $user->name }}<a
                                        class="heading-elements-toggle"><i
                                            class="icon-more"></i></a></h6>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Image</th>
                                        <th>Title</th>
                                        <th class="text-center">Created</th>
                                        <th class="text-center">Earnings</th>
                                        <th class="text-center">Sessions</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($user->posts as $post)
                                        <tr>
                                            <td class="text-center">
                                                @if(!isset($billing))
                                                    <img src="{{ $post['image'] }}" class="img-circle img-sm"/>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ url($post['slug']) }}">{{ $post['title'] }}</a> | <a
                                                        href="{{url('dashboard/post/' . $post['id'])}}"> (edit)</a>
                                            </td>
                                            <td>{{ \App\Models\DateTimeExtensions::toRelative($post['created_at']) }}</td>
                                            <td><div style="margin-right: 3px" class="alert alert-success text-center">${{ number_format($post['price_per_post'], 2) }}</div>
                                            <td>
                                                {{ $post['analytics'][1] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

<!--js bottom-->
@section('js-bottom')
@endsection
