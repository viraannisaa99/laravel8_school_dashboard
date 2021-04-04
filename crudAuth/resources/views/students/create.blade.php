@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12 margin-tb">
		<div class="pull-left">
			<h2>Add New Student</h2>
		</div>
		<div class="pull-right">
			<a class="btn btn-primary" href="{{ route('students.index') }}"> Back</a>
		</div>
	</div>
</div>

@if ($errors->any())
<div class="alert alert-danger">
	<strong>Whoops!</strong> There were some problems with your input.<br><br>
	<ul>
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif


<form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Name:</strong>
				<input type="text" name="name" class="form-control" placeholder="Name">
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>NIM:</strong>
				<input type="text" name="nim" class="form-control" placeholder="NIM">
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Phone:</strong>
				<input type="text" name="phone" class="form-control" placeholder="Phone">
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Email:</strong>
				<input type="text" name="email" class="form-control" placeholder="Email">
			</div>
		</div>
		
		<!-- Binding to room table -->
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Class:</strong>
				<select name="roomId" id="" class="form-controll">
				@foreach ($rooms as $room)
					<option value = "{{ $room->roomId }}">
						{{ $room->room }}
					</option>
				@endforeach
				</select>
			</div>
		</div>
		<!-- End of binding -->
		
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Photo:</strong>
				<input type="file" name="photo" class="form-control">
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 text-center">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
	</div>


</form>



@endsection