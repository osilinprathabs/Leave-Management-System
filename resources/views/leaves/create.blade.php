@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-plus-circle"></i> Submit Leave Request
                        </h5>
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Leave History
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Leave Balance Info -->
                  

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle"></i> Please correct the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leaves.store') }}" id="leaveForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">
                                    <i class="fas fa-tag"></i> Leave Type <span class="text-danger">*</span>
                                </label>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach($leaveTypes as $key => $value)
                                        <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="duration" class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Duration
                                </label>
                                <input type="text" id="duration" class="form-control" readonly placeholder="Select dates to calculate">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">
                                    <i class="fas fa-calendar-day"></i> Start Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="start_date" id="start_date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       value="{{ old('start_date') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">
                                    <i class="fas fa-calendar-day"></i> End Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="end_date" id="end_date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       value="{{ old('end_date') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="reason" class="form-label">
                                    <i class="fas fa-comment"></i> Reason
                                </label>
                                <textarea name="reason" id="reason" rows="4" 
                                          class="form-control @error('reason') is-invalid @enderror" 
                                          placeholder="Provide a reason for your leave request (optional)"
                                          maxlength="500">{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Maximum 500 characters
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                        <i class="fas fa-paper-plane"></i> Submit Leave 
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

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="fas fa-check-circle"></i> Success!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h4>Leave Request Submitted Successfully!</h4>
                <p class="text-muted">Your leave request has been submitted and will be reviewed by the admin.</p>
                <p class="text-muted">You will receive an email notification once the status is updated.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const duration = document.getElementById('duration');
    const typeSelect = document.getElementById('type');
    const form = document.getElementById('leaveForm');
    const submitBtn = document.getElementById('submitBtn');

    function calculateDuration() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            if (diffDays > 0 && diffDays <= 30) {
                duration.value = diffDays + ' day' + (diffDays > 1 ? 's' : '');
                duration.className = 'form-control text-success';
            } else if (diffDays > 30) {
                duration.value = diffDays + ' days (Exceeds 30-day limit)';
                duration.className = 'form-control text-danger';
            } else {
                duration.value = 'Invalid date range';
                duration.className = 'form-control text-danger';
            }
        } else {
            duration.value = '';
            duration.className = 'form-control';
        }
    }

    function validateLeaveBalance() {
        const type = typeSelect.value;
        const durationValue = duration.value;
        const days = parseInt(durationValue);
        
        if (type && durationValue && !isNaN(days)) {
            if (days > 20) {
                alert('Warning: This is a large number of days. Please ensure you have sufficient leave balance.');
            }
        }
    }

    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        calculateDuration();
    });

    endDate.addEventListener('change', calculateDuration);
    typeSelect.addEventListener('change', validateLeaveBalance);

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.disabled = true;
        
        // Submit the form
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                // Show success modal
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
                
                // Reset form after modal is closed
                document.getElementById('successModal').addEventListener('hidden.bs.modal', function() {
                    form.reset();
                    duration.value = '';
                    duration.className = 'form-control';
                    window.location.href = '{{ route("leaves.index") }}';
                });
            } else {
                // Handle errors
                return response.text().then(text => {
                    throw new Error('Server error');
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the leave request. Please try again.');
        })
        .finally(() => {
            // Reset button state
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Leave Request';
            submitBtn.disabled = false;
        });
    });
});
</script>
@endsection
