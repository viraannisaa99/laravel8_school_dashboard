@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Students</h2>
        </div>
        <div class="pull-right">
            @can('student-create')
            <a class="btn btn-success pull-right modal-show" href="{{ route('students.create') }}"> Create New Student</a>
            @endcan
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<table class="table table-bordered data-table" id="datatable">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
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
        var table = $('#datatable').DataTable({
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
    });
</script>
@endpush