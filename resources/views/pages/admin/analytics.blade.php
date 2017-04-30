@extends('layouts.dashboard')

@section('title', '- Analytics')

@section('css')
@endsection

@section('content')
    <div class="row">
        <form class="form-horizontal" action="{{url('/dashboard/analytics')}}" method="get">
            <div class="form-group">
                <label class="col-md-1 control-label">Date Range:</label>
                <div class="col-md-2">
                    <input type="text" name="dateRange" class="form-control daterange-basic" value="{{ (new DateTime())->modify('-7 days')->format('m/d/Y'). ' - ' .
                    (new DateTime())->format('m/d/Y') }}">
                </div>
                <div class="col-md-1">            <input type="submit" value="Filter" class="btn btn-primary legitRipple"/>
                </div>
            </div>
        </form>
    </div>

    <div class="panel panel-flat" id="data">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table tasks-list table-lg postizePostList">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Address</th>
                        <th>Sessions</th>
                        <th>Pageviews</th>
                        <th class="text-center">Avg. Pageviews per Session</th>
                        <th class="text-center">Bounce Rate %</th>
                        <th class="text-center">Avg. Session Duration</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td>
                                {{ $post['title'] }}
                            </td>
                            <td>
                                {{ $post['author']['name'] }}
                            </td>
                            <td>
                                <a href="http://{{config('custom.app-domain') . $post['analytics'][0]}}">{{$post['analytics'][0]}}</a>
                            </td>
                            <td>
                                {{number_format($post['analytics'][1], 0, '.', ',') }}
                            </td>
                            <td>
                                {{number_format($post['analytics'][2], 0, '.', ',') }}
                            </td>
                            <td class="text-center">
                                {{number_format($post['analytics'][3], 2, '.', ',') }}
                            </td>
                            <td class="text-center">
                                {{number_format($post['analytics'][4], 2, '.', ',') }}%
                            </td>
                            <td class="text-center">
                                {{number_format($post['analytics'][5], 0, '.', ',') }} seconds
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js-bottom')
    <script type="text/javascript">
        $('.daterange-basic').daterangepicker({
            applyClass: 'bg-slate-600',
            cancelClass: 'btn-default'
        });
    </script>
    @endsection