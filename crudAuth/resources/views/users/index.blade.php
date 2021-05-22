@extends('layouts.app')

@section('content')
<div class="container">
    <a class="btn btn-success" href="javascript:void(0)" id="createNewUser"> Create New User</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@section('modal')
<form id="userForm" name="userForm" class="form-horizontal" method="POST">

    {{ csrf_field() }}

    <div class="alert alert-danger print-error-msg" style="display:none">
        <ul></ul>
    </div>

    <input type="hidden" name="id" id="id">

    <div class="form-group">
        <strong>Name:</strong>
        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
    </div>
    <div class="form-group">
        <strong>Email:</strong>
        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
    </div>
    <div class="form-group">
        <strong>Role:</strong>
        {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
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

            ajax: "{{ route('table.user') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                }, {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'roles',
                    name: 'roles'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#createNewUser').click(function() {
            $('#saveBtn').val("create-user");
            $('#id').val('');
            $('#userForm').trigger("reset");
            $('#modelHeading').html("Create New User");
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Save');

            $.ajax({
                data: $('#userForm').serialize(),
                url: "{{ route('users.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        alert(data.success);
                        $('#userForm').trigger("reset");
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
            var rt = "{{ route('users.store') }}" + '/' + id;
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
