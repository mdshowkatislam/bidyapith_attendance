@extends('admin.master')

@section('admin_content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 pt-5">
            <h2 class="text-success" style="font-family:'Courier New', Courier, monospace">Update Shift</h2>
            <a href="{{ route('shift.index') }}" class="btn btn-success">Shift List</a>
        </div>

        <div class="alert alert-danger d-none" id="error-message"></div>

        <form id="formData">
            @csrf
            <input type="hidden" name="shift_uid" value="{{ $data['shift']['uid'] }}">
            
            <div class="row">
                <!-- Branch -->
                <div class="col-md-6 mb-3">
                    <label>Branch <span class="text-danger">*</span></label>
                    <select name="branch_code" class="form-control" id="branch_code">
                        @foreach($data['branches'] as $branch)
                            <option value="{{ $branch['branch_code'] }}" {{ $data['shift']['branch_code'] == $branch['branch_code'] ? 'selected' : '' }}>
                                {{ $branch['branch_code'] }} - {{ $branch['branch_name_en'] }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger error-text" data-error="branch_code"></span>
                </div>

                <!-- Shift Name English -->
                <div class="col-md-6 mb-3">
                    <label>Shift Name (English) <span class="text-danger">*</span></label>
                    <input type="text" name="shift_name_en" class="form-control" id="shift_name_en" value="{{ $data['shift']['shift_name_en'] }}">
                    <span class="text-danger error-text" data-error="shift_name_en"></span>
                </div>

                <!-- Shift Name Bangla -->
                <div class="col-md-6 mb-3">
                    <label>Shift Name (Bangla)</label>
                    <input type="text" name="shift_name_bn" class="form-control" id="shift_name_bn" value="{{ $data['shift']['shift_name_bn'] }}">
                    <span class="text-danger error-text" data-error="shift_name_bn"></span>
                </div>

                <!-- Start Time -->
                <div class="col-md-6 mb-3">
                    <label>Start Time <span class="text-danger">*</span></label>
                    <input type="time" name="start_time" class="form-control" id="start_time" 
                           value="{{ \Carbon\Carbon::parse($data['shift']['start_time'])->format('H:i') }}">
                    <span class="text-danger error-text" data-error="start_time"></span>
                </div>

                <!-- End Time -->
                <div class="col-md-6 mb-3">
                    <label>End Time <span class="text-danger">*</span></label>
                    <input type="time" name="end_time" class="form-control" id="end_time" 
                           value="{{ \Carbon\Carbon::parse($data['shift']['end_time'])->format('H:i') }}">
                    <span class="text-danger error-text" data-error="end_time"></span>
                </div>

                <!-- EIIN -->
                <div class="col-md-6 mb-3">
                    <label>EIIN Number</label>
                    <input type="number" name="eiin" class="form-control" id="eiin" value="{{ $data['shift']['eiin'] }}">
                    <span class="text-danger error-text" data-error="eiin"></span>
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control" id="status">
                        <option value="1" {{ $data['shift']['status'] == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $data['shift']['status'] == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="col-md-12 mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" id="description" rows="3">{{ $data['shift']['description'] }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Update Shift</button>
                    <a href="{{ route('shift.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Function to convert 24h time to 12h with AM/PM
            function convertTo12Hour(time24) {
                if (!time24) return '';
                
                const [hours, minutes] = time24.split(':');
                const hourInt = parseInt(hours, 10);
                const period = hourInt >= 12 ? 'PM' : 'AM';
                const hour12 = hourInt % 12 || 12; // Convert 0 to 12 for 12 AM
                
                return `${hour12.toString().padStart(2, '0')}:${minutes} ${period}`;
            }
          var updateUrl = "{{ route('shift.update', ['uid' => $data['shift']['uid']]) }}";
            $('#formData').on("submit", function(e) {
                e.preventDefault();
                
                // Reset messages
                $(".error-text").text('');
                $("#error-message").addClass('d-none').text('');

                // Convert times to proper format
                const startTime24 = $("#start_time").val();
                const endTime24 = $("#end_time").val();
                const startTime12 = convertTo12Hour(startTime24);
                const endTime12 = convertTo12Hour(endTime24);

                // Convert to integers
                const branchCode = parseInt($("#branch_code").val());
                const eiinValue = $("#eiin").val().trim();
                const eiin = eiinValue ? parseInt(eiinValue) : null;

                // Collect form data
                const formData = {
                    branch_code: branchCode,
                    shift_name_en: $("#shift_name_en").val().trim(),
                    shift_name_bn: $("#shift_name_bn").val().trim(),
                    start_time: startTime12,
                    end_time: endTime12,
                    eiin: eiin,
                    status: $("#status").val(),
                    description: $("#description").val().trim(),
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PUT'
                };

                console.log('Sending update data:', formData); // Debug log

                // const uid = $('input[name="shift_uid"]').val();
                // const updateUrl = "/shift/update/" + uid;

                // Validation
                let isValid = true;
                
                if (!formData.branch_code || isNaN(formData.branch_code)) {
                    isValid = false;
                    $('[data-error="branch_code"]').text("Valid branch selection is required!");
                }
                
                if (!formData.shift_name_en) {
                    isValid = false;
                    $('[data-error="shift_name_en"]').text("Shift Name (English) is Required!");
                }
                
                if (!formData.start_time) {
                    isValid = false;
                    $('[data-error="start_time"]').text("Start Time is Required!");
                }
                
                if (!formData.end_time) {
                    isValid = false;
                    $('[data-error="end_time"]').text("End Time is Required!");
                }

                if (isValid) {
                    $.ajax({
                        url: updateUrl,
                        type: "POST", // Use POST with _method override
                        data: formData,
                        success: function(response) {
                            localStorage.setItem('success_msg', response.message);
                            window.location.href = "{{ route('shift.index') }}";
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                // Handle validation errors
                                const errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    $(`[data-error="${field}"]`).text(errors[field][0]);
                                }
                            } else {
                                $("#error-message").removeClass('d-none')
                                    .text('An error occurred while updating the shift.');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection