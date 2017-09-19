@extends('layouts.app')
@section('title')
    Search
@endsection

@section('css')
    <link href="{{ asset('css/user-index.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-3">Search: {{ $key }}</div>
                            <div class="col-md-7">
                                <div id="div_search">
                                    <form action="{{url('/users/search')}}" method="GET">
                                        <input type="text" class="form-control" name="key" id="key" onkeyup="search_autocomplete()" placeholder="Search...........">
                                        <ul id="list_item">
                                        </ul>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-2"><a href="{{url('/users/create')}}" class="btn btn-info ">Create user</a></div>
                        </div>
                    </div>

                    @if (Session::has('success'))
                        <div id="message" class="alert alert-info">{{ Session::get('success') }}</div>
                    @endif
                    <div id="message"></div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>User name</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Manager</th>
                                <th>Email</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th><input type="checkbox" name="check[]" class="check" value="{{ $user->id }}"></th>
                                    <th>{{ $user->id }}</th>
                                    <th>{{ $user->username }}</th>
                                    <th>{{ $user->name }}</th>
                                    <th>{{ $user->name }}</th>
                                    <th>@if ($user->is_manager == 1) Yes @else No @endif</th>
                                    <th>{{ $user->email }}</th>
                                    <th>
                                        <a href="{{ url('users/edit/'.$user->id) }}" class="edit btn-link">Edit</a> /
                                        <form action="{{ url('users/delete/'.$user->id) }}" method="DELETE">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button type="submit" onclick="return confirm('Are you sure?')" class="btn-link">Delete</button>
                                        </form> /
                                        <form action="{{ url('users/resetpassword/'.$user->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button type="submit" onclick="return confirm('Are you sure?')" class="btn-link">Reset Password</button>
                                        </form>
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <a class="btn btn-success" href="" onclick="return confirm('Are you sure?') ? resetMultiplePassword() : ''">Reset password</a>
                        <a class="btn btn-primary" href="{{ url('users/exportToExcel') }}" onclick="return confirm('Are you sure?')">Export to excel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')

    <meta name="_token" content="{!! csrf_token() !!}" />
    <script src="{{ asset('js/user-index.js') }}"></script>


@endsection
