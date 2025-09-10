@extends('admin.master')

@section('admin_content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 pt-4">
            <h2 class="text-success"
                style="font-family:'Courier New', Courier, monospace">Update Branch</h2>
            <a href="{{ route('branch.index') }}"
               class="btn btn-success">Branch List</a>
        </div>

        <div class="alert alert-danger d-none"
             id="error-message"></div>

        <form id="formData">
            @csrf
            <input type="hidden"
                   name="branch_uid"
                   value="{{ $branch['uid'] }}">
            <div class="row">
                <!-- Branch Id -->
                <div class="col-md-6 mb-3">
                    <label for="branch_name"
                           class="form-label">Branch Code <span class="text-danger">*</span> </label>
                    <input type="integer"
                           name="branch_code"
                           class="form-control"
                           id="branch_code"
                           value="{{ $branch['branch_code'] }}">
                    <span class="text-danger error-text"
                          data-error="branch_code"></span>
                </div>
                <!-- Branch Name English -->
                <div class="col-md-6 mb-3">
                    <label for="branch_name"
                           class="form-label">Branch Name(English) <span class="text-danger">*</span></label>
                    <input type="text"
                           name="branch_name_en"
                           class="form-control"
                           id="branch_name_en"
                           value="{{ $branch['branch_name_en'] }}">
                    <span class="text-danger error-text"
                          data-error="branch_name_en"></span>
                </div>
                <!-- Branch Name Bangla -->
                <div class="col-md-6 mb-3">
                    <label for="branch_name"
                           class="form-label">Branch Name(Bangla)</label>
                    <input type="text"
                           name="branch_name_bn"
                           class="form-control"
                           id="branch_name_bn"
                           value="{{ $branch['branch_name_bn'] }}">
                    <span class="text-danger error-text"
                          data-error="branch_name_bn"></span>
                </div>
                <!-- Branch Location -->
                <div class="col-md-6 mb-3">
                    <label for="branch_name"
                           class="form-label">Branch Location</label>
                    <input type="text"
                           name="branch_location"
                           class="form-control"
                           id="branch_location"
                           value="{{ $branch['branch_location'] }}">
                    <span class="text-danger error-text"
                          data-error="branch_location"></span>
                </div>
                <!-- Branch Head -->
                <div class="col-md-6 mb-3">
                    <label for="branch_head"
                           class="form-label">Branch Head</label>
                    <input type="integer"
                           name="head_of_branch_id"
                           class="form-control"
                           id="head_of_branch_id"
                           value="{{ $branch['head_of_branch_id'] }}">
                    <span class="text-danger error-text"
                          data-error="head_of_branch_id"></span>
                </div>
                <!-- Eiin Number -->
                <div class="col-md-6 mb-3">
                    <label for="eiin"
                           class="form-label">Eiin Number</label>
                    <input type="text"
                           name="eiin"
                           class="form-control"
                           id="eiin"
                           value="{{ $branch['eiin'] }}">
                    <span class="text-danger error-text"
                          data-error="eiin"></span>
                </div>
                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label for="status"
                           class="form-label">Status</label>
                    <select name="rec_status"
                            class="form-control"
                            id="rec_status">
                        <option value="1"
                                {{ $branch['rec_status'] == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0"
                                {{ $branch['rec_status'] == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-md-12">
                    <button type="submit"
                            class="btn btn-success saveButton">Update Branch</button>
                    <a href="{{ route('branch.index') }}"
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

            var updateUrl = "{{ route('branch.update', $branch['uid']) }}";


            $('#formData').on("submit", function(e) {
                e.preventDefault();

                // Reset messages
                $(".error-text").text('');
                $("#error-message").addClass('d-none').text('');

                // Collect form data
                var formData = {
                    branch_code: $("#branch_code").val().trim(),
                    branch_name_en: $("#branch_name_en").val().trim(),
                    branch_name_bn: $("#branch_name_bn").val().trim(),
                    branch_location: $("#branch_location").val().trim(),
                    head_of_branch_id: $("#head_of_branch_id").val().trim(),
                    rec_status: $("#rec_status").val(),
                    eiin: $("#eiin").val().trim(),
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PUT' // This tells Laravel to treat as PUT request
                };

                // Validation
                let isValid = true;
                if (!formData.branch_name_en) {
                    isValid = false;
                    $("#branch_name_en").next(".error-text").text("Branch Name (English) is Required !");
                }
                if (!formData.branch_code) {
                    isValid = false;
                    $("#branch_code").next(".error-text").text("Branch Code is Required !");
                }

                // If valid, submit
                if (isValid) {
                    console.log("Sending PUT request to:", updateUrl);

                    $.ajax({
                        url: updateUrl,
                        type: "POST", // Use POST but override with _method
                        data: formData,
                        success: function(response) {
                            localStorage.setItem('success_msg', response.message);
                            window.location.href = "{{ route('branch.index') }}";
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    $(`[data-error="${field}"]`).text(errors[field][0]);
                                }
                            } else {
                                $("#error-message").removeClass('d-none')
                                    .text('An error occurred: ' + (xhr.responseJSON?.message ||
                                        'Please try again.'));
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
