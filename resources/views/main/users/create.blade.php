@extends('layouts.main')

@section('title')
    Add user
@stop

@section('content')
    <header style="margin-top:42px;margin-left:0px;padding-left:48px;border-bottom:2px solid #c1c1c1;padding-bottom:6px;">
        <h2>Add User</h2>
    </header>
    <div class="row" style="margin-top:35px;">
        <div class="col-lg-8 offset-2">
            <form id="edit-user-form" method="POST" action="/user">
                {{csrf_field()}}
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label class="col-form-label" for="name">Name</label></div>
                        <div class="col-lg-9 col-xl-8">
                            <input id="name" class="form-control" type="text" name="name" placeholder="Enter name">
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
                            <input id="username" class="form-control" type="text" name="username" placeholder="Enter Username">
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
                        <div class="col-lg-9 col-xl-8"><select id="role_id" name="role_id" class="form-control">
                                <option value="" selected="">-- Select role --</option>
                                <option value="1">Manager</option>
                                <option value="2">Seller</option>
                            </select>
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
                            <input id="contact" class="form-control" type="tel" name="contact" placeholder="Contact">
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
                        <div class="col-lg-9 col-xl-8"><select id="isActive" name="isActive" class="form-control"><option selected value="0">Not Active</option><option value="1">Active</option></select></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-4 offset-5 text-center"><button class="btn btn-success btn-block" type="submit"><i class="fa fa-check"></i>&nbsp;Submit</button></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop