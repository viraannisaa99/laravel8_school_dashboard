@extends('layouts.app')

@section('content')

<div class="container">
    <a class="btn btn-success" href="javascript:void(0)" id="createNewStudent"> Create New Student</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>NIM</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@section('modal')
<form id="studentForm" name="studentForm" class="form-horizontal" enctype="multipart/form-data" method="POST">

    {{ csrf_field() }}

    <div class="alert alert-danger print-error-msg" style="display:none">
        <ul></ul>
    </div>

    <input type="hidden" name="id" id="id">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-12">
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
            <span class="error text-danger d-none"></span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">NIM</label>
        <div class="col-sm-12">
            <textarea id="nim" name="nim" placeholder="Enter NIM" class="form-control"></textarea>
            <span class="error text-danger d-none"></span>
        </div>
    </div>

    <div class="form-group">
        <label for="phone" class="col-sm-2 control-label">Phone</label>
        <div class="col-sm-12">
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone" value="" maxlength="50" required="">
            <span class="error text-danger d-none"></span>
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-12">
            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="" maxlength="50" required="">
            <span class="error text-danger d-none"></span>
        </div>
    </div>

    <!-- Binding to room table -->

    <div class="form-group">
        <strong>Class:</strong>
        <select name="roomId" id="roomId" class="form-control">
            @foreach ($rooms as $room)
            <option value="{{ $room->roomId }}">
                {{ $room->room }}
            </option>
            @endforeach
        </select>
    </div>
    <!-- End of binding -->

    <div class="form-group">
        <strong>Photo:</strong>
        <input type="file" name="photo" id="photo" class="form-control">
    </div>

    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes</button>
    </div>

</form>
@endsection

@endsection

@push('scripts')
<script type="text/javascript">
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        /**
         * AJAX show datatable
         */
        var table = $('.data-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('table.student') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                }, {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'nim',
                    name: 'nim'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        $('#createNewStudent').click(function() {
            $('#saveBtn').val("create-student");
            $('#id').val('');
            $('#studentForm').trigger("reset");
            $('#modelHeading').html("Create New Student");
            $('#ajaxModel').modal('show');
        });
        $('body').on('click', '.editStudent', function() {
            event.preventDefault();
            var id = $(this).data('id');
            $.get("{{ route('students.index') }}" + '/' + id + '/edit', function(data) {
                $('#modelHeading').html("Edit Student");
                $('#saveBtn').val("edit-student"); //keep
                $('#ajaxModel').modal('show');
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#nim').val(data.nim);
                $('#phone').val(data.phone);
                $('#email').val(data.email);
                $('#roomId').val(data.roomId);
                $('#photo').attr(data.photo);
            })
        });
        $('#studentForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                data: formData,
                url: "{{ route('students.store') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        alert(data.success);
                        $('#studentForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                    }else{
                        printErrorMsg(data.error);
                    }
                },
            });
        });
        /** 
         * AJAX button delete
         */
        $('body').on('click', '.btn-delete', function() {
            event.preventDefault();
            var id = $(this).data("id");
            var rt = "{{ route('students.store') }}" + '/' + id;
            confirm("Are You sure want to delete !");
            $.ajax({
                type: "DELETE",
                url: rt,
                success: function(data) {
                    table.draw();
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });
        function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }
    });
</script>

@endpush

@if (count($errors) > 0)
<script type="text/javascript">
    $(document).ready(function() {
        $('#studentForm').modal('show');
    });
</script>
@endif