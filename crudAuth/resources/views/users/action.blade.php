<a href="{{ $url_show }}" class="btn-show showUser" data-id="{{ $user->id }}">Show</a> 

@can('user-create', 'user-edit', 'user-delete')
<a href="{{ $url_edit }}" class="modal-show edit" data-id="{{ $user->id }}">Edit</i></a> 

<a href="{{ $url_destroy }}" class="btn-delete deleteUser" data-id="{{ $user->id }}">Delete</i></a>
@endcan
