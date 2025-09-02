@extends('admin.master')

@section('admin_content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 pt-5">
            <h2 class="text-success"
                style="font-family:'Courier New', Courier, monospace">
                {{ isset($holiday) ? 'Update Holiday' : 'Add Holiday' }}
            </h2>
            <a href="{{ route('holiday.index') }}"
               class="btn btn-success">Holiday List</a>
        </div>

        <form id="formData">
            @csrf
            @if (isset($holiday))
                <input type="hidden"
                       name="holiday_id"
                       value="{{ $holiday->id }}">
            @endif

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Holiday Name</label>
                    <input type="text"
                           name="holiday_name"
                           class="form-control"
                           value="{{ $holiday->holiday_name ?? '' }}">
                    <span class="text-danger error-text"
                          data-error="holiday_name"></span>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Start Date</label>
                    <input type="date"
                           name="start_date"
                           class="form-control"
                           value="{{ $holiday->start_date ?? '' }}">
                    <span class="text-danger error-text"
                          data-error="start_date"></span>
                </div>

                <div class="col-md-4 mb-3">
                    <label>End Date</label>
                    <input type="date"
                           name="end_date"
                           class="form-control"
                           value="{{ $holiday->end_date ?? '' }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Status</label>
                    <select name="status"
                            class="form-control">
                        <option value="1"
                                {{ isset($holiday) && $holiday->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0"
                                {{ isset($holiday) && $holiday->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Description</label>
                    <textarea name="description"
                              class="form-control">{{ $holiday->description ?? '' }}</textarea>
                </div>

                <div class="col-md-12">
                    <button type="submit"
                            class="btn btn-primary">
                        {{ isset($holiday) ? 'Update' : 'Save' }}
                    </button>
                    <a href="{{ route('holiday.index') }}"
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
            $('#formData').on('submit', function(e) {
                e.preventDefault();

                let formData = {
                    holiday_name: $('input[name="holiday_name"]').val(),
                    start_date: $('input[name="start_date"]').val(),
                    end_date: $('input[name="end_date"]').val(),
                    status: $('select[name="status"]').val(),
                    description: $('textarea[name="description"]').val()
                };

                let isValid = true;
                $(".error-text").remove();
                if (formData.holiday_name === "") {
                    isValid = false;
                    $('input[name="holiday_name"]').after(
                        '<small class="text-danger error-text">Holiday Name is required!</small>');

                }
                if (formData.start_date === "") {
                    isValid = false;
                    $('input[name="start_date"]').after(
                        '<small class="text-danger error-text">Start Date is required!</small>');

                }
                if (formData.end_date !== "") {
                    let startDate = new Date(formData.start_date);
                    let endDate = new Date(formData.end_date);

                    if (endDate <= startDate) {
                        isValid = false;
                        $('input[name="end_date"]').after(
                            '<small class="text-danger error-text">End Date cannot be before Start Date!</small>'
                        );
                    }
                }


                if (isValid) {

                    let holidayId = $('input[name="holiday_id"]').val();
                    let requestType = holidayId ? 'PUT' : 'POST';
                    let ajaxUrl = holidayId ?
                        "http://attendance2.localhost.com/api/holiday_manage/update/" + holidayId :
                        "http://attendance2.localhost.com/api/holiday_manage/store";

                    $.ajax({
                        url: ajaxUrl,
                        type: requestType,
                        data: formData,
                        success: function(response) {
                            localStorage.setItem('holiday_success_msg', response.message);
                            window.location.href = "{{ route('holiday.index') }}";
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                alert('Validation Error:\n' + JSON.stringify(xhr.responseJSON
                                    .errors, null, 2));
                            } else {
                                alert('An error occurred while submitting the form.');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
