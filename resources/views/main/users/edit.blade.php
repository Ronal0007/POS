@extends('layouts.main')

@section('title')
    Edit user
@stop

@section('content')
    <header style="margin-top:42px;margin-left:0px;padding-left:48px;border-bottom:2px solid #c1c1c1;padding-bottom:6px;">
        <h2>Edit User</h2>
    </header>


    {{--{!! Form::open(['method'=>'','action'=>'']) !!}--}}
        {{--<div class="form-group">--}}
            {{--{!! Form::label('name','Name') !!}--}}
            {{--{!! Form::text('name',null,['class'=>'form-control']) !!}--}}
        {{--</div>--}}
    {{--{!! Form::close() !!}--}}





    <div class="row" style="margin-top:35px;">
        <div class="col-lg-8 offset-2">
                {!! Form::model($user,['method'=>'PUT', 'action'=>['UserController@update',$user->id]]) !!}
                <div class="form-group ">
                    <div class="form-row">
                        <div class="col-lg-3 text-center">
                            <label class="col-form-label" for="name">Name</label>
                        </div>
                        <div class="col-lg-9 col-xl-8 ">
                            {!! Form::text('name',null,['id'=>'name','class'=>'form-control','placeholder'=>'Enter name']) !!}
                            @if ($errors->has('name'))
                                <span class="text-danger">
                                <small>{{ $errors->first('name') }}</small>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label class="col-form-label" for="username">Username</label></div>
                        <div class="col-lg-9 col-xl-8">
                            {!! Form::text('username',null,['id'=>'username','class'=>'form-control','placeholder'=>'Enter username']) !!}

                            @if ($errors->has('username'))
                                <span class="text-danger">
                                <small>{{ $errors->first('username') }}</small>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label class="col-form-label" for="role_id">Role</label></div>
                        <div class="col-lg-9 col-xl-8">
                            {!! Form::select('role_id',[''=>'-- Select role --']+$roles,null,['id'=>'role_id','class'=>'form-control']) !!}

                            @if ($errors->has('role_id'))
                                <span class="text-danger">
                                <small>Select user role</small>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label class="col-form-label" for="phoneno">Contact</label></div>
                        <div class="col-lg-9 col-xl-8">
                            {!! Form::text('contact',null,['id'=>'contact','class'=>'form-control','placeholder'=>'Enter contact']) !!}
                            @if ($errors->has('contact'))
                                <span class="text-danger">
                                <small>{{ $errors->first('contact') }}</small>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label class="col-form-label" for="isActive">Status</label></div>
                        <div class="col-lg-9 col-xl-8">
                            {!! Form::select('isActive',[0=>'Not Active',1=>'Active'],null,['id'=>'isActive','class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-4 offset-3 pull-left">
                    <div class="form-row">
                        <button class="btn btn-success btn-block" type="submit"><i class="fa fa-check"></i>&nbsp;Update</button>
                    </div>
                </div>
            {!! Form::close() !!}

            <div class="col-lg-4 pull-right lg" style="margin-right: 3em;">
                {!! Form::open(['method'=>'HEAD', 'action'=>['UserController@show',$user->id]]) !!}
                        <button type="submit" class="btn btn-danger btn-block"><i class="fa fa-trash"></i> Delete</button>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
@stop