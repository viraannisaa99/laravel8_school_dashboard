@extends('layouts.app')

@section('content')
<div class="container">
    <a class="btn btn-success" href="javascript:void(0)" id="createNewArticle"> Create New Article</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Details</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@section('modal')
<form id="articleForm" name="articleForm" class="form-horizontal">
    <input type="hidden" name="id" id="id">
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Title</label>
        <div class="col-sm-12">
            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="" maxlength="50" required="">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Details</label>
        <div class="col-sm-12">
            <textarea id="detail" name="detail" required="" placeholder="Enter Details" class="form-control"></textarea>
        </div>
    </div>

    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
        </button>
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

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('table.article') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'detail',
                    name: 'detail'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#createNewArticle').click(function() {
            $('#saveBtn').val("create-article");
            $('#id').val('');
            $('#articleForm').trigger("reset");
            $('#modelHeading').html("Create New Article");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editArticle', function() {
            event.preventDefault();
            var id = $(this).data('id');
            $.get("{{ route('articles.index') }}" + '/' + id + '/edit', function(data) {
                $('#modelHeading').html("Edit Article");
                $('#saveBtn').val("edit-article"); //keep
                $('#ajaxModel').modal('show');
                $('#id').val(data.id);
                $('#title').val(data.title);
                $('#detail').val(data.detail);
            })
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Save');

            $.ajax({
                data: $('#articleForm').serialize(),
                url: "{{ route('articles.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    $('#articleForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        $('body').on('click', '.deleteArticle', function() {
            event.preventDefault();
            var id = $(this).data("id");
            confirm("Are You sure want to delete !");

            $.ajax({
                type: "DELETE",
                url: "{{ route('articles.store') }}" + '/' + id,
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

</html>