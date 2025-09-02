@extends('admin.master')

@section('admin_content')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div class="container pt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-success">Working Days</h2>
            <button class="btn btn-success"
                    id="createBtn">Add New</button>
        </div>
        <div class="row d-flex justify-content-center">
            <div id="formContainer"
                 class="col-md-4"
                 style="display: none;">
                @include('admin.frontend.work_day.form')
            </div>
        </div>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Day Name</th>
                    <th>Is Weekend</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="workDayTableBody">
                @foreach ($work_days as $day)
                    <tr data-id="{{ $day['id'] }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $day['day_name'] }}</td>
                        <td>{{ $day['is_weekend'] ? 'Yes' : 'No' }}</td>
                        <td>
                            <button class="btn btn-sm btn-info editBtn"
                                    data-id="{{ $day['id'] }}">Edit</button>
                            <button class="btn btn-sm btn-danger deleteBtn"
                                    data-id="{{ $day['id'] }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="js/jquery-3.6.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Toastr global options
        toastr.options = {
            closeButton: true,
            progressBar: true,
            timeOut: 15000,
            extendedTimeOut: 8000,
            positionClass: "toast-top-right"
        };
    </script>
@endsection

@section('scripts')
    <script>
        $(function() {
            const form = $('#workDayForm');

            // Hover effect
            $('table tbody tr').hover(
                function() {
                    $(this).addClass('table-active');
                },
                function() {
                    $(this).removeClass('table-active');
                }
            );

            // Show form for create
            $('#createBtn').on('click', function() {
                $('#formContainer').show();
                form.trigger('reset').attr('data-action', 'create');
                form.find('input[name="id"]').val('');
                $('#formTitle').text('Add Working Day').css({
                    'font-family': `'Courier New', Courier, monospace`,
                    'color': '#28a745'
                });
                $('.text-danger').text('');
            });

            // Form submit (create/update)
            form.on('submit', function(e) {
                e.preventDefault();

                const actionType = form.attr('data-action');
                const id = form.find('input[name="id"]').val();
                const url = actionType === 'edit' ?
                    `http://attendance2.localhost.com/api/day_manage/update/${id}` :
                    `http://attendance2.localhost.com/api/day_manage/store`;

                const formData = {
                    day_name: form.find('[name=day_name]').val(),
                    is_weekend: form.find('[name=is_weekend]').is(':checked') ? 1 : 0,
                    _token: '{{ csrf_token() }}'
                };

                if (actionType === 'edit') formData._method = 'PUT';

                // Disable button
                form.find('button[type="submit"]').prop('disabled', true).text('Saving...');

                $.post(url, formData)
                    .done(function(response) {
                        toastr.success(response.message || 'Saved successfully!');
                        form.trigger('reset');
                        $('#formContainer').hide();
                        location.reload();
                    })
                    .fail(function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        $('.text-danger').text('');
                        if (errors.day_name) $('#day_name_error').text(errors.day_name[0]);
                        if (errors.is_weekend) $('#is_weekend_error').text(errors.is_weekend[0]);
                    })
                    .always(function() {
                        form.find('button[type="submit"]').prop('disabled', false).text('Submit');
                    });
            });

            // Edit button
            $('.editBtn').on('click', function() {
                const id = $(this).data('id');
                $('#formTitle').text('Edit Working Day').css({
                    'font-family': `'Courier New', Courier, monospace`,
                    'color': '#007bff'
                });
                form.attr('data-action', 'edit');
                $('.text-danger').text('');

                $.get(`http://attendance2.localhost.com/api/day_manage/edit/${id}`, function(data) {
                    $('#formContainer').show();
                    form.find('input[name="id"]').val(data.id);
                    form.find('input[name="day_name"]').val(data.day_name);
                    form.find('input[name="is_weekend"]').prop('checked', data.is_weekend == 1);
                }).fail(function() {
                    toastr.error('Failed to load day data');
                });
            });

            // Delete button
            $('.deleteBtn').on('click', function() {
                const id = $(this).data('id');
                if (!confirm('Are you sure you want to delete this?')) return;

                $.ajax({
                    url: `http://attendance2.localhost.com/api/day_manage/delete/${id}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        toastr.success('Deleted successfully');
                        location.reload();
                    },
                    error: function() {
                        toastr.error('Failed to delete');
                    }
                });
            });
        });
    </script>
@endsection
