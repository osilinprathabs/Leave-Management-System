@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-plus"></i> Add New Employee
                        </h5>
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Employees
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.employees.store') }}" id="employeeForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user"></i> Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" id="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i> Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password" id="password" 
                                       class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Minimum 8 characters</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock"></i> Confirm Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Create Employee
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeForm = document.getElementById('employeeForm');

    // Handle form submission with SweetAlert2
    employeeForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json' // Request JSON response
            },
            body: formData
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (status === 200 || status === 201) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: body.message || 'Employee created successfully.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = "{{ route('admin.employees.index') }}";
                });
            } else if (status === 422) {
                let errorsHtml = '<ul>';
                for (let key in body.errors) {
                    errorsHtml += `<li>${body.errors[key][0]}</li>`;
                }
                errorsHtml += '</ul>';
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error!',
                    html: errorsHtml,
                    confirmButtonText: 'Got It'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: body.message || 'Something went wrong. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Network Error!',
                text: 'Could not connect to the server. Please check your internet connection.',
                confirmButtonText: 'OK'
            });
        });
    });
});
</script>
@endsection
