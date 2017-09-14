@extends('layouts.app')
@section('title')
    {{ $user->name }}
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">My infomation</div>

                <div class="panel-body">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td>User Name</td>
                                <td>{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>{{ $user->department->name }}</td>
                            </tr>
                            <tr>
                                <td>Is Manager</td>
                                <td>@if ($user->is_manager == 1) Yes @else No @endif</td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ url('infomation/edit') }}" class="btn btn-info">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
