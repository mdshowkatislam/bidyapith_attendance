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
                <!-- Shift Name -->
                <div class="col-md-6 mb-3">
                    <label for="shift_name"
                           class="form-label">Shift Name</label>
                    <input type="text"
                           name="shift_name"
                           class="form-control"
                           id="shift_name">
                    <span class="text-danger error-text"
                          data-error="shift_name"></span>

                </div>

                <!-- Start Time -->
                <div class="col-md-6 mb-3">
                    <label for="start_time"
                           class="form-label">Start Time</label>
                    <input type="time"
                           name="start_time"
                           class="form-control"
                           id="start_time">
                    <span class="text-danger error-text"
                          data-error="start_time"></span>

                </div>

                <!-- End Time -->
                <div class="col-md-6 mb-3">
                    <label for="end_time"
                           class="form-label">End Time</label>
                    <input type="time"
                           name="end_time"
                           class="form-control"
                           id="end_time">
                    <span class="text-danger error-text"
                          data-error="end_time"></span>

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
            $('#formData').on("submit", function(e) {
                e.preventDefault();
                // Reset messages
                $(".error-text").text('');

                $("#error-message").addClass('d-none').text('');


                var shiftName = $("#shift_name").val().trim();
                let startTimeRaw = $('input[name="start_time"]').val(); // e.g., "14:30"
                let endTimeRaw = $('input[name="end_time"]').val();

                // Convert to "h:i A" format (e.g., "02:30 PM")
                function convertToAmPm(time24) {
                    if (!time24) return '';
                    let [hour, minute] = time24.split(':');
                    hour = parseInt(hour, 10);
                    const ampm = hour >= 12 ? 'PM' : 'AM';
                    hour = hour % 12 || 12;
                    const hourStr = hour < 10 ? '0' + hour : hour; // ✅ Add leading zero
                    return `${hourStr}:${minute} ${ampm}`;
                }
                const startTimeFormatted = convertToAmPm(startTimeRaw);
                const endTimeFormatted = convertToAmPm(endTimeRaw);


                var formStatus = $("#status").val();
                var formText = $("#description").val().trim();



                let isValid = true;
                if (shiftName === "") {
                    isValid = false;
                    $("#shift_name").next(".error-text").text("Shift Name is Requeird !");

                }
                if (startTimeRaw === "") {
                    isValid = false;
                    $("#start_time").next(".error-text").text("Start Time is Requeird !");

                }
                if (endTimeRaw === "") {
                    isValid = false;
                    $("#end_time").next(".error-text").text("End Time is Requeird !");

                }

                if (isValid) {
                    $.ajax({
                        url: "http://attendance2.localhost.com/api/shift_manage/store",
                        type: "POST",
                        data: {
                            shift_name: shiftName,
                            start_time: startTimeFormatted,
                            end_time: endTimeFormatted,
                            status: formStatus,
                            description: formText
                        },

                        success: function(response) {

                            localStorage.setItem('success_msg', response.message);
                            window.location.href = "{{ route('shift.index') }}";
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                console.log('validation error:' + JSON.stringify(xhr
                                    .responseJSON.errors));
                            } else {
                                console.log('An error occurred while updating.');
                            }

                        }
                    });
                }

            });
        });
    </script>
@endsection
