@extends('layouts.dashboard')

@section('title', '- Manage Posts')

@section('css')
@endsection

@section('content')
    @if($numberOfPostsRequiringRevision > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning alert-styled-left">
                <span class="text-semibold">You have post(s) that require your attention. They have been sent back to you with comments. Filter posts by the 'Requires Revision' option below.</span>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-2">
            <a href="{{url('dashboard/post')}}" class="btn bg-indigo-400 btn-labeled btn-rounded"><b><i
                            class="glyphicon glyphicon-plus"></i></b> Create Post</a><br><br>
        </div>
        <form class="form-horizontal"
              action="{{ Request::fullUrl() }}"
              method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-3">
                        <select name="statusFilter" class="form-control">
                            <option value="-1" {{ Session::get('statusFilter') == null || Session::get('statusFilter') == -1 ? 'selected' : '' }}>All Posts</option>
                            <option value="0" {{ Session::get('statusFilter') == 0 ? 'selected' : '' }}>In Progress (Draft)</option>
                            <option value="1" {{ Session::get('statusFilter') == 1 ? 'selected' : '' }}>Published</option>
                            <option value="3" {{ Session::get('statusFilter') == 3 ? 'selected' : '' }}>Ready For Review</option>
                            <option value="4" {{ Session::get('statusFilter') == 4 ? 'selected' : '' }}>Requires Revision</option>
                        </select>
            </div>
            <div class="col-md-3">
                <select name="postsPerPageFilter" class="form-control">
                    <option value="20" {{ Session::get('postsPerPageFilter') == 20 ? 'selected' : '' }}>Show 20 Posts Per Page</option>
                    <option value="50" {{ Session::get('postsPerPageFilter') == 50 ? 'selected' : '' }}>Show 50 Posts Per Page</option>
                    <option value="100" {{ Session::get('postsPerPageFilter') == 100 ? 'selected' : '' }}>Show 100 Posts Per Page</option>
                    @if(Auth::user()->type == \App\Models\UserType::Administrator)
                        <option value="1000" {{ Session::get('postsPerPageFilter') == 1000 ? 'selected' : '' }}>Show 1000 Posts Per Page</option>
                    @endif
                </select>
                </div>
            <div class="col-md-3">
                <input style="margin-left:20px" type="submit" class="btn btn-primary legitRipple" value="Filter Posts"/>
            </div>
        </form>
    </div>
    <div class="text-center">{!! $posts->links() !!}</div><br />
    <div class="panel panel-flat" id="data">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table tasks-list table-lg postizePostList">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Total Clicks</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Author</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td>{{$post->id}}</td>
                            <td><img src="{{$post->image}}" class="postizeListThumb"></td>
                            <td>
                                <h4>{{$post->title}}</h4>
                                {{$post->description}}
                                <br><br>
                                <a href="{{ url($post->slug) }}" target="_blank">{{ url($post->slug) }}</a>
                            </td>
                            <td>
                                <span class="label border-left-violet label-striped">{{$post->category_name}}</span>
                            </td>
                            <td class="text-center">
                                {{ $post->clicks_all_time }}
                            </td>
                            <td class="text-center postizeStatusWrap">
                                @if($post->status == \App\Models\PostStatus::Enabled)
                                    <i class="icon-checkmark-circle text-success" data-popup="tooltip" title=""
                                       data-original-title="Published"></i><br>
                                @elseif($post->status == \App\Models\PostStatus::ReadyForReview)
                                    <i class="icon-question4 text-info" data-popup="tooltip" title=""
                                       data-original-title="Ready For Review"></i><br>
                                @elseif($post->status == \App\Models\PostStatus::Pending)
                                <i class="icon-question4 text-info" data-popup="tooltip" title=""
                                   data-original-title="Draft"></i><br>
                                @elseif($post->status == \App\Models\PostStatus::RequiresRevision)
                                <i class="icon-question4 text-info" data-popup="tooltip" title=""
                                   data-original-title="Requires Revision"></i><br>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($post->author_image)
                                    <img src="{{$post->author_image}}" class="img-circle img-md" alt=""
                                         data-popup="tooltip" title="" data-original-title="{{$post->author_name}}">
                                @else
                                    <span class="label label-flat border-indigo text-indigo-600">{{$post->author_name}}</span>
                                @endif
                            </td>
                            <td>
                                @if(Auth::user()->type == \App\Models\UserType::Administrator || Auth::user()->type == \App\Models\UserType::Moderator || $post->user_id == Auth::user()->id)
                                    <a href="{{url('dashboard/post/' . $post->id) }}"
                                       class="btn bg-indigo-400 btn-labeled btn-rounded"><b><i
                                                    class="glyphicon glyphicon-edit"></i></b> Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="text-center">{!! $posts->links() !!}</div>
@endsection

@section('js-bottom')
    <script>
        $(document).ready(function () {
            $('.delete-post').click(function (e) {
                e.preventDefault();

                var post = $(this).attr('data-post-id');
                $(this).parent().append('<img class="loading" src="{{ asset("assets/img/loading.gif") }}" />');
                $.ajaxSetup({
                    headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
                });
                $.ajax({
                    type: "POST",
                    url: "{{ url('dashboard/post/delete') }}",
                    cache: false,
                    context: this,
                    data: {
                        postId: post,
                    },

                    success: function (data) {

                        $(this).parent().children('img').remove();
                        $(this).parent().parent().remove();
                        alert(data);

                    },
                    error: function (response) {

                        $(this).parent().children('img').remove();
                        alert(response);
                    },
                });
            });
        });
    </script>
@endsection
