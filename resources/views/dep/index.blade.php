@include('header.navbar')
<!DOCTYPE html>
<html lang="en">

<head>
    @include('layout.header')
</head>

<body class="bg-light" style="margin-bottom: 22px; margin-left:200px; cursor: inherit;">

    <div class="container">
        <div class="card mt-5">
            <h3 class="card-header p-3 text-center fw-bold">
                <i class="bi bi-card-list"></i> Department's List
            </h3>

            @if (Auth::user()->role === 'admin')
                <button type="button" class="btn btn-success rounded-3 mt-2 ms-2" style="width:180px; height: 40px;"
                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="bi bi-person-add"></i> Add Department
                </button>

                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="false" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">

                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">
                                    <i class="bi bi-person-add me-1"></i>Create New Department
                                </h1>
                                <button tsype="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form action="{{ route('dep.store') }}" id="addDepartmentForm" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Department Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter Name" value="{{ old('name') }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="description" class="form-label fw-semibold">Description</label>
                                        <textarea name="description" class="form-control" id="description" rows="3" placeholder="Enter Description">{{ old('description') }}</textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-person-add"></i> Add Department
                                        </button>
                                        <a href="{{ route('dep.index') }}" class="btn btn-secondary ms-2"
                                            data-bs-dismiss="modal">Close
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card-body">
                <table class="table table-bordered table-striped departmentTable" id="departmentTable">
                    <thead class="text-center">
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Department Name</th>
                            <th class="text-center">Description</th>
                            <th class="text-center" style="width: 30%">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="departmentShowModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Department's Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Id:</strong> <span id="department-id"></span></p>
                    <p><strong>Department Name:</strong> <span id="department-name"></span></p>
                    <p><strong>Description:</strong> <span id="department-description"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editDepartmentModalLabel">
                        <i class="bi bi-pencil-square"></i> Edit Department
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="updateDepartmentForm" method="POST">
                        @csrf
                        @method('POST')

                        <div class="mb-3">
                            <label for="edit-name" class="form-label fw-semibold">Department Name</label>
                            <input type="text" name="name" class="form-control" id="edit-name"
                                value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label for="edit-description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" id="edit-description" rows="3">
                            {{ old('description') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-floppy"></i> Save
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    @include('layout.script')

    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#departmentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dep.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#addDepartmentForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "{{ route('dep.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#addDepartmentForm')[0].reset();
                        $('#staticBackdrop').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'success',
                            title: 'New Record Created Successfully!',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    },
                });
            });

            $('body').on('click', '#edit-department', function() {
                var userURL = $(this).data('url');

                $.get(userURL, function(data) {
                    $('#updateDepartmentForm').attr('action', '/dep/update/' + data.id);
                    $('#editDepartmentModal').modal('show');
                    $('#edit-name').val(data.name);
                    $('#edit-description').val(data.description);
                });
            });

            $('#updateDepartmentForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#updateDepartmentForm')[0].reset();
                        $('#editDepartmentModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'success',
                            title: 'Record Updated Successfully',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    },
                });
            });

            $('body').on('click', '#show-department', function() {
                var userURL = $(this).data('url');
                $.get(userURL, function(data) {
                    $('#departmentShowModal').modal('show');
                    $('#department-id').text(data.id);
                    $('#department-name').text(data.name);
                    $('#department-description').text(data.description);
                })
            });

            $(document).on('click', '#delete-user', function() {
                let userId = $(this).data('id');
                let deleteUrl = "{{ route('dep.destroy', ':id') }}";
                deleteUrl = deleteUrl.replace(':id', userId);

                swal({
                    title: "Are you sure?",
                    text: "Are you sure want to delete record?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#df4e31ff",
                    confirmButtonText: "Yes, Delete!",
                    cancelButtonColor: "#0000ffb0",
                    cancelButtonText: "No, Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                swal("Deleted!", "Record Deleted Successfully!",
                                    "success");
                                table.ajax.reload();
                                setTimeout(function() {
                                    swal.close();
                                }, 1000);
                            },
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
