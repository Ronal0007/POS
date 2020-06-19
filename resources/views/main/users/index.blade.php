@extends('layouts.main')

@section('title')
    Users
@stop

@section('content')
    <header style="margin-top:42px;margin-left:0px;padding-left:48px;border-bottom:2px solid #c1c1c1;padding-bottom:6px;">
        <h2>Users</h2>
    </header>
    @if(session('status'))
        <div class="row" style="margin-top: 10px;">
            <div class="col-lg-6 offset-3">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button  type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{session('status')}}
                </div>
            </div>
        </div>
    @endif
    <div class="row" style="margin-top:35px;">
        <div class="col-lg-12 col-xl-11 offset-lg-0">
            <div class="row">
                <div class="col-lg-12">
                    <a href="{{route('excel.excel')}}" class="btn btn-success"><i class="ion ion-printer"></i>Print Excel</a>
                    <a href="{{route('user.create')}}" class="btn btn-success pull-right"><i class="ion ion-android-person-add"></i>  Add New User</a>
                </div>
            </div>
            <div class="row" style="margin-top:19px;">
                <div class="col offset-lg-0">
                        <table id="user-table" class="table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($users)>0)
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->role->name}}</td>
                                        <td>{{$user->contact}}</td>
                                        <td>{{$user->isActive==1 ? 'Active':'Not Active'}}</td>
                                        <td>{{$user->created_at->diffForHumans()}}</td>
                                        <td>{{$user->updated_at->diffForHumans()}}</td>
                                        <td>
                                            <div class="row ">
                                                <div class="col-lg-3 offset-4 pull-right">
                                                    <a href="{{route('user.edit',$user->slug)}}" class="btn text-info"><i class="ion ion-edit"></i></a>
                                                </div>
                                                @if(Auth::user()->id!= $user->id)
                                                    <div class="col-lg-3 pull-right">
                                                        {!! Form::open(['method'=>'DELETE', 'action'=>['UserController@destroy',$user->id]]) !!}
                                                            <button type="submit" class="btn text-danger"><i class="ion ion-ios-trash"></i></button>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
@stop