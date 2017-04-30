@extends('layouts.dashboard')

@section('title', '- Article Request List')

@section('css')
@endsection

@section('content')
    <div class="panel panel-flat" id="data">
        <div class="panel-heading">
            <h5 class="panel-title">Post Request List</h5>
        </div>
        <div class="panel-body">
            <p>Welcome to the article request section! You can click on the 'Create New Post' button next to any article
                request.
                Please note you must create an article using this button in order for the added bonus to be applied.</p>
            <p>

            <p>IF AN ARTICLE HAS IMAGES SOURCED FROM OTHER WEBSITES (AKA THE 'SOURCE' BUTTON LIKE WE HAVE), PLEASE USE THE SAME SOURCE AS THEY USE, AND NOT THE SITE OF THE ARTICLE YOU'RE REWRITING
            <br><br>For example, if you were rewriting an article from http://memes.com and it sourced images from http://animals.com, you would also source the image URLs from animals.com, since they are closer to the original source.</p>

            <p>Some post requests are 'recurring', which means we might need several posts relating to 'horror' or 'cats'. You can choose to do 1 or multiple.</p>
            <div class="table-responsive">
                <table class="table tasks-list table-lg postizePostList">
                    <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Details</th>
                        <th class="text-center">Price Per Post</th>
                        <th class="text-center">Recurring</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($postRequests as $postRequest)
                        <tr>
                            <td>{{$postRequest->id}}</td>
                            <td>
                                <h4>{{$postRequest->title}}</h4>
                                {!! nl2br($postRequest->description) !!}
                                <br><br>
                            </td>
                            <td class="text-center">
                                ${{ number_format($postRequest->price_per_post, 2) }}
                            </td>
                            <td class="text-center postizeStatusWrap">
                                @if($postRequest->recurring)
                                    You can create multiple articles<br> based on this request. <br>We will remove this request when we<br> have enough articles on this topic.
                                @else
                                    One time only
                                @endif
                            </td>
                            <td>
                                <form class="create-post-from-request" action="{{ url('dashboard/post/request/assign/' . $postRequest->id) }}" method="post">
                                    <button type="submit" class="btn {{ !isset($postRequest->user_id) ? 'bg-indigo-400' : 'bg-orange-400' }} btn-labeled btn-rounded"><b><i
                                                class="glyphicon glyphicon-edit"></i></b> {{ !isset($postRequest->user_id) ? 'Create New Post' : 'Un-assign Request' }}</button>
                                    @if(isset($postRequest->user_id))
                                        <a class="btn bg-indigo-400 btn-labeled btn-rounded" href="{{ url('dashboard/post') }}?post_request_id={{$postRequest->id}}"><b><i
                                                        class="glyphicon glyphicon-edit"></i></b> Create New Post</a>
                                        @endif
                                    @if(Auth::user()->type == \App\Models\UserType::Administrator)
                                        <a href="{{url('dashboard/post/request/' . $postRequest->id)}}"
                                           class="btn bg-green-400 btn-labeled btn-rounded"><b><i
                                                        class="glyphicon glyphicon-edit"></i></b> Edit Request</a>
                                    @endif
                                </form>
                                @if(isset($postRequest->user_id))
                                    <h3>Request is assigned to {{$postRequest->user_name}} <img src="{{$postRequest->user_image}}"/></h3>
                                @endif
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
    <script>
        $(document).ready(function () {
            $('.create-post-from-request').submit(function (e) {
                if(!confirm('Proceeding will assign you to this request, or unassign you if you are already assigned. Are you sure?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
