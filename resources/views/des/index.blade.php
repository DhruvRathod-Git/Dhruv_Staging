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
                <i class="bi bi-database"></i> Designation's List
            </h3>

            @if (Auth::user()->role === 'admin')
                <button type="button" class="btn btn-success rounded-3 mt-2 ms-2" style="width:180px; height: 40px;"
                    data-bs-toggle="modal" data-bs-target="#staticBackdrop"  title="Create New Designation">
                    <i class="bi bi-person-add"></i> Add Designaiton
                </button>

                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="false" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">
                                    <i class="bi bi-person-add me-1"></i>Create New Designation
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('des.store') }}" id="addDesignationForm" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Name</label>
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
                                            <i class="bi bi-person-add"></i> Add Designation
                                        </button>
                                        <a href="{{ route('des.index') }}" class="btn btn-secondary ms-2"
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
                <table class="table table-bordered table-striped designationTable" id="designationTable">
                    <thead class="text-center">
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Designation</th>
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

    <div class="modal fade" id="designationShowModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Designation's Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Id:</strong> <span id="designation-id"></span></p>
                    <p><strong>Name:</strong> <span id="designation-name"></span></p>
                    <p><strong>Description:</strong> <span id="designation-description"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDesignationModal" tabindex="-1" aria-labelledby="editDesignationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editDesignationModalLabel">
                        <i class="bi bi-pencil-square"></i> Edit Designation
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="updateDesignationForm" method="POST">
                        @csrf
                        @method('POST')

                        <div class="mb-3">
                            <label for="edit-name" class="form-label fw-semibold">Designation</label>
                            <input type="text" name="name" class="form-control" id="edit-name"
                                value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label for="edit-description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" id="edit-description" rows="3">{{ old('description') }}</textarea>
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
            var table = $('#designationTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('des.index') }}",
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

            $('#addDesignationForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "{{ route('des.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#addDesignationForm')[0].reset();
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

            $('body').on('click', '#edit-designation', function() {
                var userURL = $(this).data('url');

                $.get(userURL, function(data) {
                    $('#updateDesignationForm').attr('action', '/des/update/' + data.id);
                    $('#editDesignationModal').modal('show');
                    $('#edit-name').val(data.name);
                    $('#edit-description').val(data.description);
                });
            });

            $('#updateDesignationForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#updateDesignationForm')[0].reset();
                        $('#editDesignationModal').modal('hide');
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

            $('body').on('click', '#show-designation', function() {
                var userURL = $(this).data('url');
                $.get(userURL, function(data) {
                    $('#designationShowModal').modal('show');
                    $('#designation-id').text(data.id);
                    $('#designation-name').text(data.name);
                    $('#designation-description').text(data.description);
                })
            });

             $(document).on('click', '#delete-user', function() {
                let userId = $(this).data('id');
                let deleteUrl = "{{ route('des.destroy', ':id') }}";
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