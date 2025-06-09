<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">User Management</h2>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>

    <table class="table table-bordered">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>CPF</th>
            <th>Email</th>
            <th>Birthday</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr data-id="{{ $user->id }}">
                <td>{{ $user->name }}</td>
                <td>{{ $user->cpf }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->birthday }}</td>
                <td>{{ $user->phone }}</td>
                <td>
                    <button class="btn btn-warning btn-sm edit-btn"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-cpf="{{ $user->cpf }}"
                            data-email="{{ $user->email }}"
                            data-birthday="{{ $user->birthday }}"
                            data-phone="{{ $user->phone }}">
                        Edit
                    </button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $user->id }}">Delete</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('users.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('users.form-fields')
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="editUserForm" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('users.form-fields', ['prefix' => 'edit'])
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function () {
        $('.edit-btn').click(function () {
            const user = $(this).data();
            $('#editUserForm').attr('action', `/users/${user.id}`);
            $('#edit_name').val(user.name);
            $('#edit_cpf').val(user.cpf);
            $('#edit_email').val(user.email);
            $('#edit_birthday').val(user.birthday);
            $('#edit_phone').val(user.phone);
            $('#editUserModal').modal('show');
        });

        $('.delete-btn').click(function () {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: `/users/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        location.reload();
                    },
                    error: function () {
                        alert('Error deleting user');
                    }
                });
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
