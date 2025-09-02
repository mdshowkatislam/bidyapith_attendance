@extends('admin.master')
<!-- Select2 CSS -->


@section('admin_content')

    <div class="container">
        <h2 class="text-success"
            style="font-family:'Courier New', Courier, monospace">Add Special Working Day</h2>

        <div id="message"></div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date"
                           id="date"
                           class="form-control" />
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label for="day_type">Day Type</label>
                    <select id="day_type"
                            class="form-control">
                        <option value="1">Working Day</option>
                        <option value="0">Off Day/Holiday</option>
                    </select>
                </div>
            </div>
           
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label for="group_ids">Groups <span class="text-danger"></span> </label>
                    <select id="group_ids"
                            class="form-control"
                            multiple>
                        @foreach ($allGroup as $group)
                            <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text"
                           id="description"
                           class="form-control" />
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status"
                            class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-3"></div>

            <div class="col-md-12 d-flex justify-content-center gap-3 mt-4">
                <button id="btnSave"
                        class="btn btn-success px-4 mr-3">
                    <i class="fas fa-save mr-2"></i> Save
                </button>

                <a href="{{ route('special_working_day.index') }}"
                   class="btn btn-secondary px-4">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
            </div>






        </div>


    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
    $('.select2').select2({
        width: '100%',
        allowClear: true
    });
});
</script>
    <script>
        $('#btnSave').click(function() {
            let group_ids = $('#group_ids').val(); // array
            let date = $('#date').val();
            let day_type = $('#day_type').val();
            let description = $('#description').val();
            let status = $('#status').val();

            $.ajax({
                url: "http://attendance2.localhost.com/api/shift_manage/store",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    group_ids: group_ids,
                    date: date,
                    day_type: day_type,
                    description: description,
                    status: status
                },
                success: function(response) {
                    $('#message').html('<div class="alert alert-success">Created successfully!</div>');
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
