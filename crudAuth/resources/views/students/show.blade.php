@extends('layouts.app')

@section('content')

<div class="row justify-content-md-center">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="{{ url('student_photo/'.$student->photo) }}" width="200px" alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{ $student->name }}</h3>
                <p class="text-muted text-center">Student</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>NIM</b> <a class="float-right">{{ $student->nim }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Phone</b> <a class="float-right">{{ $student->phone }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $student->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Class</b> <a class="float-right">{{ $student->room->room }}</a>
                    </li>
                </ul>
                <a href="{{ route('students.index') }}" class="btn btn-primary btn-block"><b>Back</b></a>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>


@endsection