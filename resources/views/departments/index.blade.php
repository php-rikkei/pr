@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">List of departments
                <a href="{{url('/departments/create')}}" class="btn btn-info ">Create department</a>
                </div>
                @if (Session::has('success'))
                    <div class="alert alert-info">{{ Session::get('success') }}</div>
                @endif
                <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                            <tr>
                                <th><input type="checkbox" name="check[]" value="{{ $department->id }}"></th>
                                <th>{{ $department->id }}</th>
                                <th>{{ $department->name }}</th>
                                <th>
                                    <a href="{{ url('departments/edit/'.$department->id) }}" class="edit">Edit</a> / 
                                    <a onclick="return confirm('Are you sure?')" href="{{ url('departments/delete/'.$department->id) }}" class="edit">Delete</a>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('js')
<!--     <script type="text/javascript">
        $('.edit').click(function () {
            alert($(this).attr('id'));

        });
    </script>
     -->
@endsection
