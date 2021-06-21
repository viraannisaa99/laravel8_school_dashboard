@extends('layouts.app')

@section('content')
<div class="container">
    @if(Auth::check('user-create'))
    <a class="btn btn-success" href="javascript:void(0)" id="createNewUser"> Create New User</a>
    @endif
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
        <small id="name_error" class="form-text text-danger"></small>
    </div>
    <div class="form-group">
        <strong>Email:</strong>
        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
        <small id="email_error" class="form-text text-danger"></small>
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
            $('#name_error').text('');
            $('#email_error').text('');
            $(this).html('Save');

            $.ajax({
                data: $('#userForm').serialize(),
                url: "{{ route('users.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.status == true) {
                        alert('Success!');
                        $('#userForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                    }
                },
                error: function(reject) {
                    var response = $.parseJSON(reject.responseText);
                    $.each(response.errors, function(key, val) {
                        $("#" + key + "_error").text(val[0]);
                    })
                }
                
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
                    console.log(data);
                }
            });
        });

    });
</script>
@endpush
