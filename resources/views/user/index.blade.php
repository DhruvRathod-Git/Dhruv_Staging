@include('header.navbar')
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    @include('layout.header')
</head>

<body class="bg-light" style="margin-left:200px; margin-bottom:20px;">

    <div class="container mt-4">

        <div class="card">
            <h3 class="card-header text-center fw-bold">
                <i class="bi bi-people-fill"></i> User List
            </h3>

            @if (Auth::user()->role === 'admin')
                <button class="btn btn-success mt-3 ms-3 mb-2 float-end" style="width: 15%" id="createNewUser">
                    <i class="bi bi-person-plus-fill"></i> Create New User
                </button>
            @endif

            <div class="card-body">
                <table id="userTable" class="table table-bordered table-striped text-center w-100">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Number</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th style="width: 25%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="modal fade" id="ajaxModel" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="userForm">
                        @csrf
                        <div class="modal-header">
                            <h5 id="modelHeading"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <input type="hidden" name="user_id" id="user_id">

                            <div class="mb-2">
                                <label class="fw-semibold">Name</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>

                            <div class="mb-2">
                                <label class="fw-semibold">Email</label>
                                <input type="email" class="form-control" name="email" id="email">
                            </div>

                            <div class="mb-2">
                                <label class="fw-semibold">Password</label>
                                <input type="password" class="form-control" name="password" id="password">
                            </div>

                            <div class="mb-2">
                                <label class="fw-semibold">Number</label>
                                <input type="number" class="form-control" name="number" id="number">
                            </div>

                            <div class="mb-2">
                                <label class="fw-semibold">Designation</label>
                                <select name="designation" id="designation" class="form-select">
                                    <option selected disabled>Select Designation</option>
                                    @foreach ($Designation as $des)
                                        <option value="{{ $des->name }}">{{ $des->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="fw-semibold">Department</label>
                                <select name="department" id="department" class="form-select">
                                    <option selected disabled>Select Department</option>
                                    @foreach ($Department as $dep)
                                        <option value="{{ $dep->name }}">{{ $dep->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" id="saveBtn" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="userShowModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>ID:</strong> <span id="user-id"></span></p>
                        <p><strong>Name:</strong> <span id="user-name"></span></p>
                        <p><strong>Email:</strong> <span id="user-email"></span></p>
                        <p><strong>Number:</strong> <span id="user-number"></span></p>
                        <p><strong>Designation:</strong> <span id="user-designation"></span></p>
                        <p><strong>Department:</strong> <span id="user-department"></span></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('layout.script')

    <script>
        $(document).ready(function() {

            var table = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.index') }}",

                pagingType: "full_numbers",
                pageLength: 10,

                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'number',
                        name: 'number'
                    },
                    {
                        data: 'designation',
                        name: 'designation'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#createNewUser').click(function() {
                $('#user_id').val('');
                $('#userForm')[0].reset();
                $('#modelHeading').html("<i class='bi bi-person-plus-fill'></i> Create New User");
                $('#ajaxModel').modal('show');
            });

            $('#userForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('user.store') }}",
                    method: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function() {
                        $('#ajaxModel').modal('hide');
                        table.ajax.reload();

                        swal("Success!", "User saved successfully!", "success");
                    }
                });
            });

            $('body').on('click', '#show-user', function() {
                $.get($(this).data('url'), function(data) {
                    $('#userShowModal').modal('show');
                    $('#user-id').text(data.id);
                    $('#user-name').text(data.name);
                    $('#user-email').text(data.email);
                    $('#user-number').text(data.number);
                    $('#user-designation').text(data.designation);
                    $('#user-department').text(data.department);
                });
            });

            $('body').on('click', '#edit-user', function() {
                $.get($(this).data('url'), function(data) {

                    $('#modelHeading').html("Edit User");
                    $('#ajaxModel').modal('show');

                    $('#user_id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#number').val(data.number);
                    $('#designation').val(data.designation);
                    $('#department').val(data.department);

                });
            });

            $('body').on('click', '#delete-user', function() {
                let url = $(this).data('url');
                swal({
                    title: "Are you sure?",
                    text: "Delete this user?",
                    icon: "warning",
                    buttons: true,
                }).then((yes) => {
                    if (yes) {
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function() {
                                swal("Deleted!", "User deleted.", "success");
                                table.ajax.reload();
                            }
                        });
                    }
                });
            });

        });
    </script>

</body>

</html>
