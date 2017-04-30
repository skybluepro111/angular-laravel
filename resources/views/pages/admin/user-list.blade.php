@extends('layouts.dashboard')

@section('title', '- Manage User')

@section('css')
@endsection

@section('content')
    <div class="row">
        <a href="{{url('dashboard/user')}}" class="btn bg-indigo-400 btn-labeled btn-rounded"><b><i
                        class="glyphicon glyphicon-plus"></i></b> Create User</a><br><br>
    </div>

    <div class="panel panel-flat" id="data">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table tasks-list table-lg postizePostList">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th class="text-center">Picture</th>
                        <th>Details</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Type</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td class="text-center">
                                @if ($user->image)
                                    <img src="{{$user->image}}" class="img-circle img-md" alt="" data-popup="tooltip"
                                         title="" data-original-title="{{$user->name}}">
                                @else
                                    <span class="label label-flat border-indigo text-indigo-600">No picture</span>
                                @endif
                            </td>
                            <td>
                                <h4>{{$user->name}}</h4>
                                {{$user->email}}
                            </td>
                            <td class="text-center postizeStatusWrap">
                                @if($user->status == 0)
                                    <i class="icon-checkmark-circle text-success" data-popup="tooltip" title=""
                                       data-original-title="Enabled"></i><br>
                                @else
                                    <i class="icon-question4 text-info" data-popup="tooltip" title=""
                                       data-original-title="Disabled"></i><br>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($user->type == 1)
                                    Administrator
                                @else
                                    Normal User
                                @endif
                            </td>
                            <td>
                                <a href="{{url('dashboard/user/' . $user->id) }}"
                                   class="btn bg-indigo-400 btn-labeled btn-rounded"><b><i
                                                class="glyphicon glyphicon-edit"></i></b> Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="text-center">{!! $users->links() !!}</div>
@endsection
