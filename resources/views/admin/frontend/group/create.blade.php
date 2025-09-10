@extends('admin.master')
@section('plugins.Select2', true)
@section('admin_content')

<style>
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
    }
    .select2-selection__choice {
        background-color: #007bff;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
    }
</style>

<div class="container">
    <h2 class="text-success" style="font-family:'Courier New', Courier, monospace">Add Group</h2>

    <div id="message"></div>
    <div class="row g-3">
        <!-- Group Name -->
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="group_name">Group Name <span class="text-danger">*</span></label>
                <input type="text" id="group_name" class="form-control" />
            </div>
        </div>

        <!-- Description -->
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
        </div>

        <!-- Shift Selection (Includes Branch Information) -->
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="shift_id">Select Shift <span class="text-danger">*</span></label>
                <select id="shift_id" class="form-control select2bs4" style="width: 100%;">
                    <option value="">-- Choose Shift --</option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift['id'] }}" 
                                data-branch-code="{{ $shift['branch_code'] }}"
                                data-branch-name="{{ $shift['branch_name'] }}">
                            {{ $shift['shift_name_en'] }} (Branch: {{ $shift['branch_code'] }} - {{ $shift['branch_name'] }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Branch Display (Read-only) -->
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label>Branch</label>
                <input type="text" id="branch_display" class="form-control" readonly>
                <small class="text-muted">Automatically determined by selected shift</small>
            </div>
        </div>

        <!-- Work Days -->
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="work_days">Work Days <span class="text-danger">*</span></label>
                <select id="work_days" class="form-control select2" multiple="multiple" style="width:100%">
                    @foreach ($workDays as $wd)
                        <option value="{{ $wd->id }}">{{ $wd->day_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Employees -->
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="employees">Employees <span class="text-danger">*</span></label>
                <select id="employees" class="form-control select2" multiple="multiple" style="width:100%">
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Flexible Times -->
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="flexible_in_time">Flexible In Time (1-59 minutes)</label>
                <input type="number" id="flexible_in_time" class="form-control" min="1" max="59">
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="flexible_out_time">Flexible Out Time (1-59 minutes)</label>
                <input type="number" id="flexible_out_time" class="form-control" min="1" max="59">
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" class="form-control">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <!-- Buttons -->
        <div class="col-12 d-flex justify-content-center gap-3 mt-4">
            <button id="btnSave" class="btn btn-success px-4 mr-4">
                <i class="fas fa-save mr-2"></i> Save
            </button>
            <a href="{{ route('group_manage.index') }}" class="btn btn-secondary px-4">
                <i class="fas fa-times mr-2"></i> Cancel
            </a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Init select2
        $('.select2').select2({
            width: '100%',
            allowClear: true,
            placeholder: 'Select options'
        });

        // Update branch display when shift is selected
        $('#shift_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const branchCode = selectedOption.data('branch-code');
            const branchName = selectedOption.data('branch-name');
            
            if (branchCode && branchName) {
                $('#branch_display').val(`${branchCode} - ${branchName}`);
            } else {
                $('#branch_display').val('');
            }
        });

        // Save button click
        $('#btnSave').on('click', function(e) {
            e.preventDefault();

            const group_name = $('#group_name').val().trim();
            const description = $('#description').val().trim();
            const shift_id = $('#shift_id').val();
            const flexInTime = $('#flexible_in_time').val() ? parseInt($('#flexible_in_time').val()) : null;
            const flexOutTime = $('#flexible_out_time').val() ? parseInt($('#flexible_out_time').val()) : null;
            const status = $('#status').val();
            const work_day_ids = $('#work_days').val() || [];
            const employee_ids = $('#employees').val() || [];

            // Validation
            let errorMessage = "";
            if (!group_name) errorMessage += "Group name is required.<br>";
            if (!shift_id) errorMessage += "Shift selection is required.<br>";
            if (work_day_ids.length === 0) errorMessage += "At least one work day is required.<br>";
            if (employee_ids.length === 0) errorMessage += "At least one employee is required.<br>";
            if (flexInTime && (flexInTime < 1 || flexInTime > 59)) errorMessage += "Flexible In Time must be between 1-59.<br>";
            if (flexOutTime && (flexOutTime < 1 || flexOutTime > 59)) errorMessage += "Flexible Out Time must be between 1-59.<br>";

            if (errorMessage) {
                $('#message').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                return;
            }

            // AJAX request
            $.ajax({
                url: "{{ route('group_manage.store') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    group_name: group_name,
                    description: description,
                    shift_id: shift_id,
                    flexible_in_time: flexInTime,
                    flexible_out_time: flexOutTime,
                    status: status,
                    work_day_ids: work_day_ids,
                    employee_ids: employee_ids
                },
                success: function(response) {
                    if (response.success) {
                        $('#message').html('<div class="alert alert-success">' + response.message + '</div>');
                        setTimeout(() => window.location.href = "{{ route('group_manage.index') }}", 1500);
                    } else {
                        $('#message').html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let msg = '<div class="alert alert-danger"><ul>';
                        $.each(errors, (k, v) => msg += `<li>${v[0]}</li>`);
                        msg += '</ul></div>';
                        $('#message').html(msg);
                    } else {
                        $('#message').html('<div class="alert alert-danger">Something went wrong.</div>');
                    }
                }
            });
        });
    });
</script>
@endsection