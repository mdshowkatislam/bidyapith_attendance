@extends('admin.master')
@section('css')
    <style>
        tr.selectable:hover {
            cursor: pointer;
            background-color: #f1f1f1;
        }

        tr.selected {
            background-color: #007bff !important;
            color: white;
        }

        #save-btn {
            opacity: 0.5;
            background-color: #007bff;
            /* Original Bootstrap primary */
            color: white;
            /* Original text color */
            transition: background-color 0.3s, color 0.3s, opacity 0.3s;
        }

        #save-btn:hover {
            background-color: #28a745;
            /* New background color on hover (green) */
            color: yellow;
            /* New text color on hover */
            opacity: 1;
            /* Fully visible on hover */
        }
    </style>
@endsection

@section('admin_content')
    <div class="content">
        <!-- Database Dropdown -->


        <!-- Result Table -->
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-body d-flex flex-column align-items-center position-relative">
                            <form id="sync-form"
                                  class="d-flex flex-column align-items-center"
                                  enctype="multipart/form-data"
                                  style="width: 100%;">

                                @csrf

                                <!-- File input + Sync time in one line -->
                                <div class="d-flex justify-content-center flex-wrap mb-4 w-100 p-3 rounded"
                                     style="gap: 50px;">

                                    <!-- File upload -->

                                    <div class="me-3">
                                        <label class="text-primary pt-2 mb-0 mr-2">
                                            <h5>Please Insert Your Database Location Url</h5>
                                        </label>
                                    </div>
                                    <div class="custom-file"
                                         style="max-width: 300px;">
                                        <input type="text"
                                               style="width:270px !important;"
                                               class="form-control"
                                               id="customFile"
                                               pattern='^(?!.*(::|"|,|;)).*$'
                                               title='Characters ::, ", , and ; are not allowed'
                                               name="location"
                                               placeholder="Url">

                                    </div>


                                    <!-- Sync time -->

                                    <div class="me-3">
                                        <label class="text-primary pt-2 mr-2">
                                            <h5>Please Insert Your Sync Time</h5>
                                        </label>
                                    </div>
                                    <div class="form-group mb-4"
                                         style="min-width: 150px;">
                                        <select class="form-control"
                                                id="syncTimeId"
                                                name="syncTimeName">
                                            <option value="1">Every Minute </option>
                                            <option value="2">Every Thirty Minutes</option>
                                            <option value="3">Hourly</option>
                                            <option value="4">Every 2 hours</option>
                                            <option value="5">Daily </option>
                                            <option value="6">Daily at (13:00pm) </option>
                                            <option value="7">Tow Time Daily ( 1, 13 )</option>
                                            <option value="8">Between('9:00'>>>'17:00')</option>


                                        </select>
                                    </div>


                                </div>

                                <!-- Change button centered below -->
                                <div class="d-flex justify-content-center w-100 mt-4">
                                    <button type="submit"
                                            id="save-btn"
                                            class="btn btn-sm btn-primary">Change</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
 <script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            // Setup CSRF token for all AJAX requests
            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });

            $('#sync-form').on('submit', function(e) {
                e.preventDefault(); // prevent default form submission

                let formData = {
                    location: $('#customFile').val(),
                    syncTimeName: $('#syncTimeId').val()
                };
                
            // alert(JSON.stringify(formData));
      

                $.ajax({
                    url: 'http://attendance2.localhost.com/api/update_time_schedule',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response.message || 'Successfully updated!');
                        // Optionally reset form or update UI
                    },
                    error: function(xhr) {
                       
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = '';
                            $.each(errors, function(key, val) {
                                errorMessages += val + '\n';
                            });
                            alert(errorMessages);
                        } else {
                            alert('Something went wrong. Please try again.');
                        }
                    }
                });
            });

        });
    </script>
@endsection

