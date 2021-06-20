@extends('layouts.app')

@section('content')

<div class="row justify-content-md-center">
    <div class="col-md-9">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <h3 class="profile-username text-center">{{ $article->title }}</h3>
                <p class="text-muted text-center">{{ $article->users->name }}</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        {{ $article->detail }}
                    </li>
                </ul>
                <p class="text-muted text-center">{{ $article->created_at }}</p>
                <a href="{{ route('articles.index') }}" class="btn btn-primary btn-block"><b>Back</b></a>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

@endsection