@extends('layouts.app')

@section('content')

<div class="row justify-content-md-center">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <h3 class="profile-username text-center">{{ $role->name }}</h3>
                <p class="text-muted text-center">Student</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Permission: </b>
                    </li>
                    <li class="list-group-item">
                        @if(!empty($rolePermissions))
                        @foreach($rolePermissions as $v)
                        <ul>
                            <li>{{ $v->name }}</li>
                        </ul>
                        @endforeach
                        @endif
                    </li>
                </ul>
                <a href="{{ route('roles.index') }}" class="btn btn-primary btn-block"><b>Back</b></a>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>


@endsection