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
        <h2 class="text-info">Edit Group</h2>

        <div id="message"></div>
        <div class="row g-3">
            <!-- Group Name -->
            <div class="col-md-4 mb-3">
                <label>Group Name</label>
                <input type="text"
                       id="group_name"
                       value="{{ $group['group_name'] }}"
                       class="form-control">
            </div>

            <!-- Description -->
            <div class="col-md-4 mb-3">
                <label>Description</label>
                <textarea id="description"
                          class="form-control">{{ $group['description'] }}</textarea>
            </div>

            <!-- Shift -->
            <div class="col-md-4 mb-3">
                <label>Select Shift</label>
                <select id="shift_id"
                        class="form-control select2bs4">
                    <option value="">-- Choose Shift --</option>
                    @foreach ($shifts as $item)
                        <option value="{{ $item['id'] }}"
                                {{ $item['id'] == $group['shift_id'] ? 'selected' : '' }}>
                            {{ $item['shift_name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- @dd($workDays[0]) --}}
            <!-- Work Days -->
            <div class="col-md-6 mb-3">
                <label>Work Days</label>
                <select id="work_days"
                        class="form-control select2"
                        multiple>

                    @foreach ($workDays as $wd)
                        <option value="{{ $wd['id'] }}"
                                {{ in_array($wd['id'], array_column($group['work_days'], 'id')) ? 'selected' : '' }}>
                            {{ $wd['day_name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            @php
                $selectedEmployeeIds = array_column($group['employees'], 'id');
            @endphp

            <div class="col-md-6 mb-3">
                <label>Employees</label>
                <select id="employees"
                        class="form-control select2"
                        multiple>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp['id'] }}"
                                {{ in_array($emp['id'], $selectedEmployeeIds) ? 'selected' : '' }}>
                            {{ $emp['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label for="flexible_in_time">Flexible In Time (between 1 and 59) </label>
                    <input type="number"
                           id="flexible_in_time"
                           class="form-control"
                            value="{{ $group['flexible_in_time'] }}"
                           min="1"
                           max="59" />


                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label for="flexible_out_time">Flexible Out Time (between 1 and 59) </label>
                    <input type="number"
                           id="flexible_out_time"
                           class="form-control"
                            value="{{ $group['flexible_out_time'] }}"
                           min="1"
                           max="59" />

                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status"
                            class="form-control">
                        <option value="1"
                                selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="col-12 d-flex justify-content-center mt-4">
                <button id="btnUpdate"
                        class="btn btn-primary px-4 mr-4">Update</button>
                <a href="{{ route('group_manage.index') }}"
                   class="btn btn-secondary px-4">Cancel</a>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                allowClear: true,
                placeholder: 'Select an option'
            });

            $('#btnUpdate').on('click', function(e) {

                e.preventDefault();

                const group_id = {{ $group['id'] }};

                const group_name = $('#group_name').val().trim();
                const description = $('#description').val().trim();
                const shift_id = $('#shift_id').val();
                const status = $('#status').val();
                const flexInTimeVal = $('#flexible_in_time').val();
                const flexOutTimeVal = $('#flexible_out_time').val();
                const flexInTime = flexInTimeVal ? parseInt(flexInTimeVal, 10) : null;
                const flexOutTime = flexOutTimeVal ? parseInt(flexOutTimeVal, 10) : null;
                const work_day_ids = $('#work_days').val() || [];
                const employee_ids = $('#employees').val() || [];
                let errorMessage = "";

                if (flexInTime !== null && (flexInTime < 1 || flexInTime > 59)) {
                    errorMessage += "Flexible In Time must be between 1 and 59.\n";
                }

                if (flexOutTime !== null && (flexOutTime < 1 || flexOutTime > 59)) {
                    errorMessage += "Flexible Out Time must be between 1 and 59.\n";
                }

                if (errorMessage !== "") {
                    $('#message').html('<div class="alert alert-danger">' + errorMessage.replace(/\n/g,
                        '<br>') + '</div>');
                    return;
                }

                const updateUrl = "http://attendance2.localhost.com/api/group_manage/update/" + group_id;


                $.ajax({
                    url: updateUrl,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        group_name,
                        description,
                        shift_id,
                        status,
                        flexInTime,
                        flexOutTime,
                        work_day_ids,
                        employee_ids,
                        _method: "PUT"
                    },
                    success: function(res) {
                        $('#message').html(
                            '<div class="alert alert-success">Updated successfully!</div>');
                        setTimeout(() => window.location.href =
                            "{{ route('group_manage.index') }}", 1000);
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let msg = '<div class="alert alert-danger"><ul>';
                            $.each(errors, (k, v) => msg += `<li>${v[0]}</li>`);
                            msg += '</ul></div>';
                            $('#message').html(msg);
                        } else {
                            $('#message').html(
                                '<div class="alert alert-danger">Something went wrong.</div>'
                            );
                        }
                    }
                });
            });
        });
    </script>
@endsection
