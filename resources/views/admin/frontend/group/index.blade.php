@extends('admin.master')

@section('admin_content')
@section('css')
    <style>
        .custom-tooltip-blue .tooltip-inner {
            background-color: blue;
            color: white;
        }

        .custom-tooltip-green .tooltip-inner {
            background-color: green;
            color: white;
        }

        .custom-tooltip-yellow .tooltip-inner {
            background-color: purple;
            color: white;
        }

        .custom-tooltip-red .tooltip-inner {
            background-color: red;
            color: white;
        }
    </style>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
      integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer" />

<script>
    $(document).ready(function() {
        // Example: Highlight row on hover
        $('table tbody tr').hover(
            function() {
                $(this).addClass('table-active');
            },
            function() {
                $(this).removeClass('table-active');
            }
        );
    });
</script>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show"
         role="alert">
        {{ session('success') }}
        <button type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show"
         role="alert">
        {{ session('error') }}
        <button type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<div class="container">
    <div class="d-flex justify-content-between pt-4">
        <h2 style="color:green;font-family:'Courier New', Courier, monospace">Group List.</h2>

        <a href="{{ route('group_manage.create') }}"
           class="btn btn-primary mb-3">Add A New Group</a>
    </div>


    <table class="table table-bordered text-center"
           id="specialWorkingDaysTable"
           style="width: 100%;">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Group Name</th>
                <th>Description</th>
                <th>Shift</th>
                <th>Flex In Time</th>
                <th>Flex Out Time</th>
                <th>Total Employees</th>
                <th>Working Days</th>
                <th>Change Status</th> 
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($groups as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['group_name'] }}</td>
                    <td>{{ $item['description'] }}</td>
                    <td>{{ $item['shift_name'] }}</td>
                    <td>{{ $item['flexible_in_time'] }} min</td>
                    <td>{{ $item['flexible_out_time'] }} min</td>
                    <td style="width:10%"
                        class="text-center">{{ $item['employee_count'] }}</td>

                    <td>
                        @forelse ($item['work_days'] as $day)
                            <span class="badge bg-primary">{{ $day }}</span>
                        @empty
                            <span class="text-muted">No Working days found</span>
                        @endforelse
                    </td>

                    <td>
                        <span class="badge status-toggle {{ $item['status'] === 'Active' ? 'bg-success' : 'bg-secondary' }}"
                              data-id="{{ $item['id'] }}"
                              style="cursor: pointer;">
                            @if ($item['status'] == 1)
                                Active
                            @else
                                Inactive
                            @endif

                        </span>
                    </td>

                    <td>
                        <button class="btn btn-info btn-sm  mb-1"
                                data-bs-custom-class="custom-tooltip-blue"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Details"><a style="color:white !important;"
                               href="{{ route('group_manage.pdf', $item['id']) }}"><i class="fa-solid fa-eye"></i></a>
                        </button>
                        <button class="btn btn-success btn-sm mb-1"
                                data-bs-custom-class="custom-tooltip-green"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="download pdf"><a style="color:white !important;"
                               href="{{ route('group_manage.download.pdf', $item['id']) }}"><i
                                   class="fa-regular fa-file-pdf"></i></a>
                        </button>
                        <button class="btn btn-warning btn-sm mb-1 mt-1"
                                data-bs-custom-class="custom-tooltip-yellow"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="edit"><a style="color:white !important;"
                               href="{{ route('group_manage.edit', $item['id']) }}"><i
                                   class="fa-solid fa-pen-fancy"></i></a>
                        </button>
                        <form action="{{ route('group_manage.delete', $item['id']) }}"
                              method="POST"
                              style="display:inline-block;color:white !important;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    data-bs-custom-class="custom-tooltip-red"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="left"
                                    title="delete"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this group?')">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="8"
                        class="text-center">No Group found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
<script>
    $(document).ready(function() {
        $('.status-toggle').click(function() {
            let $this = $(this);
            let groupId = $this.data('id');
            const updateUrl = "http://attendance2.localhost.com/api/group_manage/toggle-status/" +
                groupId;

            $.ajax({
                url: updateUrl,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },


                success: function(response) {
                    if (response.status == 1) {
                        response.status = "Active";
                    }
                    if (response.status == 0) {
                        response.status = "Inctive";
                    }

                    $this
                        .removeClass('bg-success bg-secondary')
                        .addClass(response.badge_class)
                        .text(response.status);
                },
                error: function() {
                    alert('Failed to toggle status');
                }
            });
        });
    });
</script>
@endsection
