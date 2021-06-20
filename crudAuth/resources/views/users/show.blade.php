@extends('layouts.app')


@section('content')

<div class="row justify-content-md-center">
    <div class="col-md-6">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">Admin</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Roles</b>
                            <a class="float-right">
                                @if(!empty($user->getRoleNames()))
                                @foreach($user->getRoleNames() as $v)
                                <label class="badge badge-success">{{ $v }}</label>
                                @endforeach
                                @endif
                            </a>
                        </li>
                    </ul>

                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-block"><b>Back</b></a>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>


@endsection