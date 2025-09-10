@extends('admin.master')

@section('admin_content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 pt-4">
            <h2 class="text-success"
                style="font-family:'Courier New', Courier, monospace">Add Shift</h2>
            <a href="{{ route('shift.index') }}"
               class="btn btn-success">Shift List</a>
        </div>

        <div class="alert alert-danger d-none"
             id="error-message"></div>

        <form id="formData">
            @csrf
            <div class="row">
                <!-- Branch Selection Dropdown -->
                <div class="col-md-6 mb-3">
                    <label for="branch_code"
                           class="form-label">Select Branch <span class="text-danger">*</span></label>
                    <select name="branch_code"
                            class="form-control"
                            id="branch_code"
                            required>
                        <option value="">-- Select Branch --</option>
                        @if (isset($branch) && count($branch) > 0)
                            @foreach ($branch as $br)
                                <option value="{{ $br['branch_code'] }}">
                                    {{ $br['branch_code'] }} - {{ $br['branch_name_en'] }}
                                </option>
                            @endforeach
                        @else
                            <option value="">No branches available</option>
                        @endif
                    </select>
                    <span class="text-danger error-text"
                          data-error="branch_code"></span>
                </div>

                <!-- Shift Name English -->
                <div class="col-md-6 mb-3">
                    <label for="shift_name_en"
                           class="form-label">Shift Name (English) <span class="text-danger">*</span></label>
                    <input type="text"
                           name="shift_name_en"
                           class="form-control"
                           id="shift_name_en"
                           required>
                    <span class="text-danger error-text"
                          data-error="shift_name_en"></span>
                </div>

                <!-- Shift Name Bangla -->
                <div class="col-md-6 mb-3">
                    <label for="shift_name_bn"
                           class="form-label">Shift Name (Bangla)</label>
                    <input type="text"
                           name="shift_name_bn"
                           class="form-control"
                           id="shift_name_bn">
                    <span class="text-danger error-text"
                          data-error="shift_name_bn"></span>
                </div>

                <!-- Start Time -->
                <div class="col-md-6 mb-3">
                    <label for="start_time"
                           class="form-label">Start Time <span class="text-danger">*</span></label>
                    <input type="time"
                           name="start_time"
                           class="form-control"
                           id="start_time"
                           required>
                    <span class="text-danger error-text"
                          data-error="start_time"></span>
                </div>

                <!-- End Time -->
                <div class="col-md-6 mb-3">
                    <label for="end_time"
                           class="form-label">End Time <span class="text-danger">*</span></label>
                    <input type="time"
                           name="end_time"
                           class="form-control"
                           id="end_time"
                           required>
                    <span class="text-danger error-text"
                          data-error="end_time"></span>
                </div>

                <!-- EIIN -->
                <div class="col-md-6 mb-3">
                    <label for="eiin"
                           class="form-label">EIIN Number</label>
                    <input type="number"
                           name="eiin"
                           class="form-control"
                           id="eiin">
                    <span class="text-danger error-text"
                          data-error="eiin"></span>
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label for="status"
                           class="form-label">Status</label>
                    <select name="status"
                            class="form-control"
                            id="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="col-md-12 mb-3">
                    <label for="description"
                           class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control"
                              id="description"
                              rows="3"></textarea>
                </div>

                <!-- Buttons -->
                <div class="col-md-12">
                    <button type="submit"
                            class="btn btn-success saveButton">Save Shift</button>
                    <a href="{{ route('shift.index') }}"
                       class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            function convertTo12Hour(time24) {
                if (!time24) return '';

                const [hours, minutes] = time24.split(':');
                const hourInt = parseInt(hours, 10);
                const period = hourInt >= 12 ? 'PM' : 'AM';
                const hour12 = hourInt % 12 || 12; // Convert 0 to 12 for 12 AM

                return `${hour12.toString().padStart(2, '0')}:${minutes} ${period}`;
            }

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
                    description: $("#description").val().trim()
                };


                // Validation
                let isValid = true;

                if (!formData.branch_code) {
                    isValid = false;
                    $('[data-error="branch_code"]').text("Branch selection is required!");
                }

                if (!formData.shift_name_en) {
                    isValid = false;
                    $("#shift_name_en").next(".error-text").text("Shift Name (English) is Required!");
                }

                if (!formData.start_time) {
                    isValid = false;
                    $("#start_time").next(".error-text").text("Start Time is Required!");
                }

                if (!formData.end_time) {
                    isValid = false;
                    $("#end_time").next(".error-text").text("End Time is Required!");
                }
                var updateUrl = "{{ route('shift.store') }}";
                console.log(formData);
                if (isValid) {
                    $.ajax({
                        url: updateUrl,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
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
                                    .text('An error occurred while creating the shift.');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
