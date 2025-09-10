@extends('admin.master')



@section('admin_content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3 pt-4">
            <h2 class="text-success"
                style="font-family:'Courier New', Courier, monospace">Add Branch</h2>
            <a href="{{ route('branch.index') }}"
               class="btn btn-success">Branch List</a>
        </div>

        <div class="alert alert-danger d-none"
             id="error-message"></div>


        <form id="formData">
            @csrf
            <div class="row">
                <!-- Branch Id -->
                <div class="col-md-6 mb-3">
                    <label for="branch_name"
                           class="form-label">Branch Code <span class="text-danger">*</span> </label>
                    <input type="integer"
                           name="branch_code"
                           class="form-control"
                           id="branch_code">
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
                           id="branch_name_en">
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
                           id="branch_name_bn">
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
                           id="branch_location">
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
                           id="head_of_branch_id">
                    <span class="text-danger error-text"
                          data-error="head_of_branch_id"></span>

                </div>
                <!-- Description -->
                <div class="col-md-6 mb-3">
                    <label for="eiin"
                           class="form-label">Eiin Number</label>
                    <input type="text"
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
                    <select name="rec_status"
                            class="form-control"
                            id="rec_status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>


                <!-- Buttons -->
                <div class="col-md-12">
                    <button type="submit"
                            class="btn btn-success saveButton">Save Branch</button>
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
            alert('io');
            $('#formData').on("submit", function(e) {
                e.preventDefault();
                // Reset messages
                $(".error-text").text('');

                $("#error-message").addClass('d-none').text('');

                var branchCode = $("#branch_code").val().trim();
                var branchNameEn = $("#branch_name_en").val().trim();
                var branchNameBn = $("#branch_name_bn").val().trim();
                var branchLocation = $("#branch_location").val().trim();
                var headOfBranchId = $("#head_of_branch_id").val().trim();
                var formStatus = $("#rec_status").val();
                var formEiin = $("#eiin").val().trim();


                let isValid = true;
                if (branchNameEn === "") {
                    isValid = false;
                    $("#branch_name_en").next(".error-text").text("Branch Name (English) is Required !");

                }
                if (branchCode === "") {
                    isValid = false;
                    $("#branch_code").next(".error-text").text("Branch Code is Required !");

                }
                // If the form is valid, proceed with AJAX submission
                alert('sss');
                if (isValid) {
                    $.ajax({
                        url: "http://localhost:8000/branch/store",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            branch_code: branchCode,
                            branch_name_en: branchNameEn,
                            branch_name_bn: branchNameBn,
                            branch_location: branchLocation,
                            head_of_branch_id: headOfBranchId,
                            status: formStatus,
                            eiin: formEiin
                        },


                        success: function(response) {

                            localStorage.setItem('success_msg', response.message);
                            window.location.href = "{{ route('branch.index') }}";
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
