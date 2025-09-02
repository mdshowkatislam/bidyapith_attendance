@extends('admin.master')

@section('admin_content')
    <div class="container">
        <h2 class="text-success"
            style="font-family:'Courier New', Courier, monospace">Edit Special Working Day</h2>

        <div id="message"></div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date"
                           id="date"
                           class="form-control"
                           value="{{ $specialWorkingDay['date'] }}" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="day_type">Day Type</label>
                    <select id="day_type"
                            class="form-control">
                        <option value="1"
                                {{ $specialWorkingDay['day_type'] == 1 ? 'selected' : '' }}>Working Day</option>
                        <option value="0"
                                {{ $specialWorkingDay['day_type'] == 0 ? 'selected' : '' }}>Off Day</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="group_ids">Groups</label>
                    <select id="group_ids"
                            class="form-control"
                            multiple>
                        @foreach ($specialWorkingDay['groups'] as $group)
                            <option value="{{ $group['id'] }}"
                                    {{ in_array($group['id'], $specialWorkingDay['group_ids']) ? 'selected' : '' }}>
                                {{ $group['group_name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text"
                           id="description"
                           class="form-control"
                           value="{{ $specialWorkingDay['description'] }}" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status"
                            class="form-control">
                        <option value="1"
                                {{ $specialWorkingDay['status'] == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0"
                                {{ $specialWorkingDay['status'] == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-12 d-flex justify-content-center  mt-3">
                <div class="col-md-3"> 
                    <button id="btnUpdate"
                            class="btn btn-primary mr-2">Update</button>
                    <a href="{{ route('special_working_day.index') }}"
                       class="btn btn-secondary">Cancel</a>
                </div>
            </div>

        </div>




    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script>
        $('#btnUpdate').click(function() {
            let id = "{{ $specialWorkingDay['id'] }}";
            let group_ids = $('#group_ids').val();
            let date = $('#date').val();
            let day_type = $('#day_type').val();
            let description = $('#description').val();
            let status = $('#status').val();
            const updateUrl = "http://attendance2.localhost.com/api/special_working_day/update/" + id;

            $.ajax({
                url: updateUrl,
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method:"PUT",
                    group_ids: group_ids,
                    date: date,
                    day_type: day_type,
                    description: description,
                    status: status
                },
                success: function(response) {
                    $('#message').html('<div class="alert alert-success">Updated successfully!</div>');
                    setTimeout(() => window.location.href = "{{ route('special_working_day.index') }}",
                        1000);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let msg = '<div class="alert alert-danger"><ul>';
                        $.each(errors, (k, v) => msg += `<li>${v[0]}</li>`);
                        msg += '</ul></div>';
                        $('#message').html(msg);
                    }
                }
            });
        });
    </script>
@endsection
