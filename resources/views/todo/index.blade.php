<!DOCTYPE html>
<html lang="en">

<head>
    @include('layout.header')
</head>

<body class="bg-light" style="margin-bottom: 22px; margin-left:200px; cursor: inherit;">

    @include('header.navbar')

    <div class="container">
        <div class="card mt-5">
            <h3 class="card-header p-3 text-center fw-bold">
                <i class="bi bi-person-lines-fill"></i> To-Do List
            </h3>

            @if (Auth::user()->role === 'admin')
                <a class="btn btn-success mt-2 ms-2 mb-2 rounded-3 fw-semibold" style="width:170px;"
                    href="javascript:void(0)" id="createNewTodo">
                    <i class="bi bi-plus-lg"></i> Add To-Do
                </a>

                <div class="modal fade" id="ajaxModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading"></h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="todoForm" name="todoForm" class="form-horizontal">
                                    @csrf
                                    <input type="hidden" name="todo_id" id="todo_id">

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="task" class="form-label fw-semibold">Task</label>
                                            <input type="text" class="form-control shadow-sm" id="task"
                                                name="task" placeholder="Enter task title"
                                                value="{{ old('task') }}">
                                            @error('task')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="assign" class="form-label fw-semibold">Assign</label>
                                            <select name="assign" id="assign" class="form-select shadow-sm">
                                                <option value="" disabled {{ old('assign') ? '' : 'selected' }}>
                                                    Assign To</option>
                                                @foreach ($User as $user)
                                                    <option value="{{ $user->name }}"
                                                        {{ old('assign') == $user->name ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('assign')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="progress" class="form-label fw-semibold">Progress</label>
                                            <select id="progress" name="progress" class="form-select shadow-sm">
                                                <option value="" disabled selected>Select Progress</option>
                                                <option value="Not Started">Not Started</option>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Completed">Completed</option>
                                            </select>
                                            @error('progress')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="priority" class="form-label fw-semibold">Priority</label>
                                            <select id="priority" name="priority" class="form-select shadow-sm">
                                                <option value="" disabled selected>Select Priority</option>
                                                <option value="Low">Low</option>
                                                <option value="Medium">Medium</option>
                                                <option value="Important">Important</option>
                                            </select>
                                            @error('priority')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="date" class="form-label fw-semibold">Due Date</label>
                                            <input type="date" class="form-control shadow-sm" id="date"
                                                name="date" min="{{ date('Y-m-d') }}" value="{{ old('date') }}">
                                            @error('date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="note" class="form-label fw-semibold">Notes</label>
                                            <textarea class="form-control shadow-sm" id="note" name="note" rows="3" placeholder="Add task details">{{ old('note') }}</textarea>
                                            @error('note')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-12 d-flex justify-content-between mt-3">
                                            <button type="submit" id="saveBtn"
                                                class="btn btn-success px-4 rounded-3">
                                                <i class="bi bi-plus-circle"></i> Add Task
                                            </button>
                                            <a href="{{ route('todo.index') }}"
                                                class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">
                                                <i class="bi bi-x-lg"></i> Close
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card-body">
                <table class="table table-bordered table-striped todoTable" id="todoTable">
                    <thead class="text-center">
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Task</th>
                            <th class="text-center">Assign</th>
                            <th class="text-center" style="width:10%">Date</th>
                            <th class="text-center">Progress</th>
                            <th class="text-center">Priority</th>
                            <th class="text-center">Notes</th>
                            <th class="text-center" style="width: 30%">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="todoShowModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Show Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Task:</strong> <span id="todo-task"></span></p>
                    <p><strong>Assign:</strong> <span id="todo-assign"></span></p>
                    <p><strong>Date:</strong> <span id="todo-date"></span></p>
                    <p><strong>Progress:</strong> <span id="todo-progress"></span></p>
                    <p><strong>Priority:</strong> <span id="todo-priority"></span></p>
                    <p><strong>Notes:</strong> <span id="todo-note"></span></p>
                </div>
                <div class="modal-footer text-start">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTodoModal" tabindex="-1" aria-labelledby="editTodoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editTodoModalLabel">
                        <i class="bi bi-pencil-square"></i> Edit User's Details
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="updateTodoForm" method="POST">
                        @csrf
                        @method('POST')

                        @if (Auth::user()->role === 'admin')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="task" class="form-label fw-semibold">Task</label>
                                    <input type="text" class="form-control shadow-sm" id="edit-task"
                                        name="task" value="{{ old('task') }}">
                                    @error('task')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="edit-assign" class="form-label fw-semibold">Assign To</label>
                                    <select id="edit-assign" name="assign" class="form-select"
                                        {{ Auth::user()->role !== 'admin' ? 'disabled' : '' }}>
                                        @foreach ($User as $user)
                                            <option value="{{ $user->name }}"
                                                {{ ($task->assign ?? old('assign')) == $user->name ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assign')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="progress" class="form-label fw-semibold">Progress</label>
                                    <select id="edit-progress" name="progress" class="form-select shadow-sm">
                                        <option value="" disabled {{ old('progress') == '' ? 'selected' : '' }}>
                                            Select
                                            Progress</option>
                                        <option value="Not Started"
                                            {{ old('progress') == 'Not Started' ? 'selected' : '' }}>
                                            Not Started</option>
                                        <option value="In Progress"
                                            {{ old('progress') == 'In Progress' ? 'selected' : '' }}>
                                            In Progress</option>
                                        <option value="Completed"
                                            {{ old('progress') == 'Completed' ? 'selected' : '' }}>
                                            Completed</option>
                                    </select>
                                    @error('progress')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="priority" class="form-label fw-semibold">Priority</label>
                                    <select id="edit-priority" name="priority" class="form-select shadow-sm">
                                        <option value="" disabled {{ old('priority') == '' ? 'selected' : '' }}>
                                            Select
                                            Priority</option>
                                        <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low
                                        </option>
                                        <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>
                                            Medium</option>
                                        <option value="Important"
                                            {{ old('priority') == 'Important' ? 'selected' : '' }}>
                                            Important</option>
                                    </select>
                                    @error('priority')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="date" class="form-label fw-semibold">Due Date</label>
                                    <input type="date" class="form-control shadow-sm" id="edit-date"
                                        name="date" min="{{ date('Y-m-d') }}" value="{{ old('date') }}">
                                    @error('date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="note" class="form-label fw-semibold">Notes</label>
                                    <textarea class="form-control shadow-sm" id="edit-note" name="note" rows="3">{{ old('note') }}</textarea>
                                    @error('note')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary saveEdit">
                                        <i class="bi bi-floppy"></i> Save
                                    </button>
                                    <a href="{{ route('todo.index') }}" class="btn btn-secondary"
                                        data-bs-dismiss="modal">
                                        <i class="bi bi-x-lg"></i> Close
                                    </a>
                                </div>
                            @else
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="task" class="form-label fw-semibold">Task</label>
                                        <input type="text" class="form-control shadow-sm" id="edit-task"
                                            name="task" value="{{ old('task') }}" disabled>
                                        @error('task')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="assign" class="form-label fw-semibold">Assign</label>
                                        <select name="assign" id="assign" class="form-select shadow-sm" disabled>
                                            <option value="" disabled {{ old('assign') ? '' : 'selected' }}>
                                                Assign To
                                            </option>
                                            @foreach ($User as $user)
                                                <option value="{{ $user->name }}"
                                                    {{ old('assign') == $user->name ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('assign')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="progress" class="form-label fw-semibold">Progress</label>
                                        <select id="edit-progress" name="progress" class="form-select shadow-sm">
                                            <option value="" disabled
                                                {{ old('progress') == '' ? 'selected' : '' }}>Select
                                                Progress</option>
                                            <option value="Not Started"
                                                {{ old('progress') == 'Not Started' ? 'selected' : '' }}>
                                                Not Started</option>
                                            <option value="In Progress"
                                                {{ old('progress') == 'In Progress' ? 'selected' : '' }}>
                                                In Progress</option>
                                            <option value="Completed"
                                                {{ old('progress') == 'Completed' ? 'selected' : '' }}>
                                                Completed</option>
                                        </select>
                                        @error('progress')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="priority" class="form-label fw-semibold">Priority</label>
                                        <select id="edit-priority" name="priority" class="form-select shadow-sm"
                                            disabled>
                                            <option value="" disabled
                                                {{ old('priority') == '' ? 'selected' : '' }}>Select
                                                Priority</option>
                                            <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>
                                                Low
                                            </option>
                                            <option value="Medium"
                                                {{ old('priority') == 'Medium' ? 'selected' : '' }}>
                                                Medium</option>
                                            <option value="Important"
                                                {{ old('priority') == 'Important' ? 'selected' : '' }}>
                                                Important</option>
                                        </select>
                                        @error('priority')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>


                                    <div class="col-md-4">
                                        <label for="date" class="form-label fw-semibold">Due Date</label>
                                        <input type="date" class="form-control shadow-sm" id="edit-date"
                                            name="date" min="{{ date('Y-m-d') }}" value="{{ old('date') }}"
                                            disabled>
                                        @error('date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="note" class="form-label fw-semibold">Notes</label>
                                        <textarea class="form-control shadow-sm" id="edit-note" name="note" rows="3">{{ old('note') }}</textarea>
                                        @error('note')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-floppy"></i> Save
                                        </button>
                                        <a href="{{ route('todo.index') }}" class="btn btn-secondary"
                                            data-bs-dismiss="modal">
                                            <i class="bi bi-x-lg"></i> Close
                                        </a>
                                    </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layout.script')

    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#todoTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('todo.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'task',
                        name: 'task'
                    },
                    {
                        data: 'assign',
                        name: 'assign'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'progress',
                        name: 'progress'
                    },
                    {
                        data: 'priority',
                        name: 'priority'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#createNewTodo').click(function() {
                $('#saveBtn').val("create-todo");
                $('#todo_id').val('todo.index');
                $('#todoForm').trigger("reset");
                $('#modelHeading').html("<i class='bi bi-person-lines-fill'></i> Create To-Do List");
                $('#ajaxModal').modal('show');
            });

            $('#todoForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                $('#saveBtn').html('Creating...');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('todo.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#saveBtn').html('Submit');
                        $('#todoForm').trigger("reset");
                        $('#ajaxModal').modal('hide');
                        table.ajax.reload();

                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'success',
                            title: 'New Record Created Successfully!',
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    },
                });
            });

            $('body').on('click', '#edit-todo', function() {
                var userURL = $(this).data('url');
                $.get(userURL, function(data) {
                    $('#updateTodoForm').attr('action', '/todo/update/' + data.id);
                    $('#editTodoModal').modal('show');
                    $('#edit-task').val(data.task);
                    $('#edit-assign').val(data.assign);
                    $('#edit-date').val(data.date);
                    $('#edit-progress').val(data.progress);
                    $('#edit-priority').val(data.priority);
                    $('#edit-note').val(data.note);
                });
            });

            $('#updateTodoForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        //console.log(1212121212121212)
                        $('#updateTodoForm')[0].reset();
                        $('#editTodoModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'success',
                            title: 'Record Updated Successfully',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    }
                });
            });

            $('body').on('click', '#show-todo', function() {
                var userURL = $(this).data('url');
                $.get(userURL, function(data) {
                    $('#todoShowModal').modal('show');
                    $('#todo-task').text(data.task);
                    $('#todo-assign').text(data.assign);
                    $('#todo-date').text(data.date);
                    $('#todo-progress').text(data.progress);
                    $('#todo-priority').text(data.priority);
                    $('#todo-note').text(data.note);
                });
            });

            $(document).on('click', '#delete-todo', function() {
                let todoId = $(this).data('id');
                let deleteUrl = "{{ route('todo.destroy', ':id') }}";
                deleteUrl = deleteUrl.replace(':id', todoId);

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
