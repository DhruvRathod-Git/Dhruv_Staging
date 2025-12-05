<!DOCTYPE html>
<html lang="en">

<head>
    @include('layout.header')
</head>

<body class="bg-light" style="margin-bottom:22px; margin-left:200px;">

    @include('header.navbar')

    <div class="container-fluid">
        <div class="py-5" id="content">
            <div class="row justify-content-center mt-5">
                <div class="col-lg-8 col-md-10">
                    <div class="card shadow-lg border-0 rounded-4">

                        <!-- Profile Header -->
                        <div class="card-body text-primary text-center py-3 bg-light rounded-top-4">
                            <h2 class="fw-bold m-0">
                                <i class="bi bi-person-circle"></i> Profile Details
                            </h2>
                        </div>

                        <!-- Profile Details -->
                        <div class="card-body p-4 rounded-bottom-4">
                            <div class="row align-items-center">

                                <!-- Profile Image -->
                                <div class="col-md-4 text-center mb-3">
                                    <img src="{{ $user->image ? asset('storage/' . $user->image) . '?t=' . time() : asset('default.png') }}"
                                        class="rounded-circle border shadow-lg" width="150" height="150"
                                        alt="Profile Image">
                                </div>

                                <!-- Profile Info -->
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <p class="mb-1"><strong>Name:</strong></p>
                                            <p>{{ $user->name }}</p>
                                        </div>

                                        <div class="col-sm-6">
                                            <p class="mb-1"><strong>Email:</strong></p>
                                            <p>{{ $user->email }}</p>
                                        </div>

                                        <div class="col-sm-6">
                                            <p class="mb-1"><strong>Number:</strong></p>
                                            <p>{{ $user->number }}</p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-4">
                                        <button class="btn btn-primary me-2" data-bs-toggle="modal"
                                            data-bs-target="#editProfileModal">
                                            <i class="bi bi-pencil-fill"></i> Edit
                                        </button>

                                        @if ($user->role === 'admin')
                                            <a href="{{ route('auth.homes') }}" class="btn btn-secondary">
                                                <i class="bi bi-arrow-left"></i> Back
                                            </a>
                                        @else
                                            <a href="{{ route('auth.registerhome') }}" class="btn btn-secondary">
                                                <i class="bi bi-arrow-left"></i> Back
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Profile Modal -->
                    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Edit Profile</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <form action="{{ route('profile.update', $user->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT') <!-- Use PUT for update -->

                                    <div class="modal-body">

                                        <!-- Image Preview -->
                                        <div class="text-center mb-3">
                                            <img src="{{ $user->image ? asset('storage/' . $user->image) . '?t=' . time() : asset('default.png') }}"
                                                id="image-preview" class="rounded-circle shadow mb-3" width="100"
                                                height="100" alt="Profile Image">
                                            <input type="file" name="image" id="file-upload"
                                                class="form-control @error('image') is-invalid @enderror">
                                            @error('image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Name -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Email (read-only) -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input type="email" class="form-control" value="{{ $user->email }}"
                                                disabled>
                                        </div>

                                        <!-- Number -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Number</label>
                                            <input type="number" name="number" class="form-control"
                                                value="{{ old('number', $user->number) }}" required>
                                            @error('number')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x-lg"></i> Close
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-floppy"></i> Save
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                    <!-- Live Image Preview Script -->
                    <script>
                        const fileUpload = document.getElementById('file-upload');
                        const imagePreview = document.getElementById('image-preview');

                        if (fileUpload) {
                            fileUpload.addEventListener('change', function(event) {
                                const [file] = event.target.files;
                                if (file) {
                                    imagePreview.src = URL.createObjectURL(file);
                                }
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

    @include('layout.script')

</body>

</html>
