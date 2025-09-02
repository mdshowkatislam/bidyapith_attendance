@extends('admin.master')

@section('admin_content')

    <div class="container">
<div class="d-flex justify-content-between align-items-center mb-3 pt-5">
    <h2 class="text-success" style="font-family:'Courier New', Courier, monospace">Update Shift</h2>
    <a href="{{ route('shift.index') }}" class="btn btn-success">Shift List</a>
</div>

        <form action="{{ route('shift.update', $shift['id']) }}"
              method="POST"
              id="formData">
            @csrf
            <input type="hidden"
                   name="shift_id"
                   value="{{ $shift['id'] }}">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Shift Name</label>
                    <input type="text"
                           name="shift_name"
                           class="form-control"
                           value="{{ $shift['shift_name'] }}"
                           required>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Start Time</label>
                    <input type="time"
                           name="start_time"
                           class="form-control"
                           value="{{ $shift['start_time'] }}"
                           required>
                </div>

                <div class="col-md-3 mb-3">
                    <label>End Time</label>
                    <input type="time"
                           name="end_time"
                           class="form-control"
                           value="{{ $shift['end_time'] }}"
                           required>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Status</label>
                    <select name="status"
                            class="form-control">
                        <option value="1"
                                {{ $shift['status'] == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0"
                                {{ $shift['status'] == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Description</label>
                    <textarea name="description"
                              class="form-control">{{ $shift['description'] }}</textarea>
                </div>


                <div class="col-md-12">
                    <button type="submit"
                            class="btn btn-primary">Update Shift</button>
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

                // Convert 24-hour format to 12-hour with AM/PM
                function convertToAmPm(time24) {
                    if (!time24) return '';
                    let [hour, minute] = time24.split(':');
                    hour = parseInt(hour, 10);
                    const ampm = hour >= 12 ? 'PM' : 'AM';
                    hour = hour % 12 || 12;
                    const hourStr = hour < 10 ? '0' + hour : hour; // ✅ Add leading zero
                    return `${hourStr}:${minute} ${ampm}`;
                }

                const startTimeRaw = $('input[name="start_time"]').val(); // e.g. "14:30"
                const endTimeRaw = $('input[name="end_time"]').val();

                const formData = {
                    shift_name: $('input[name="shift_name"]').val(),
                    start_time: convertToAmPm(startTimeRaw), // e.g. "2:30 PM"
                    end_time: convertToAmPm(endTimeRaw),
                    status: $('select[name="status"]').val(),
                    description: $('textarea[name="description"]').val(),

                };

                const id = $('input[name="shift_id"]').val();;
                const updateUrl = "http://attendance2.localhost.com/api/shift_manage/update/" + id;

                $.ajax({
                    url: updateUrl,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        localStorage.setItem('shift_success_msg', response.message);

                        // Redirect to index page
                        window.location.href = "{{ route('shift.index') }}";
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            alert('Validation error:\n' + JSON.stringify(xhr.responseJSON
                                .errors, null, 2));
                        } else {
                            alert('An error occurred while updating.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
