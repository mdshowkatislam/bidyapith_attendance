@extends('admin.master')

@push('css')
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Daterangepicker CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

    <style>
        .input-clear-wrapper {
            position: relative;
        }

        .clear-input {
            position: absolute;
            top: 10px;
            right: 12px;
            border-radius: 1px solid #888;
            background: transparent;
            border: none;
            font-size: 20px;
            color: #888;
            cursor: pointer;
            user-select: none;
            line-height: 1;
            padding: 0;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            transition: color 0.2s ease;
        }

        .clear-input:hover {
            color: #fa0202;
            font-size: 22px;
            text-shadow: chartreuse;


        }
    </style>
@endpush

@section('admin_content')
    <div class="container-fluid pt-4">
        <div class="col-md-12">
            <div class="card card-outline card-primary ">
                <div class="card-body d-flex flex-column align-items-center position-relative pb-0"
                     style="background: #E9F0F7">
                    {{-- @dd($shifts) --}}
                    <form id="sync-form"
                          action="{{ route('attendance.report') }}"
                          method="GET"
                          class="d-flex flex-column align-items-center"
                          style="width: 100%;">

                        @csrf
                        <div class="container mt-4 d-flex justify-content-center ">
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="shift_id">Select Shift <span class="text-danger">*</span></label>
                                    <select id="shift_id"
                                            name="shift_id"
                                            class="form-control select2bs4"
                                            style="width: 100%;"
                                            required>
                                        <option value="">-- Choose Shift --</option>
                                        @foreach ($shifts as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['shift_name_en'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9 mb-3 d-flex justify-content-center"
                                 style="background:#e7f1fa;border: 1px solid rgb(142, 199, 231); border-radius: 10px;">
                                <div class="col-md-6 mb-3 position-relative input-clear-wrapper">
                                    <label for="date_range">Select Date Range <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="date_range"
                                           name="date_range"
                                           class="form-control"
                                           placeholder="Select date range"
                                           autocomplete="off">
                                    <button type="button"
                                            class="clear-input mb-2"
                                            id="clear-date_range"
                                            title="Clear Date Range"
                                            style="display:none;">&times;</button>
                                </div>

                                <h2 class="mb-3 mt-4"
                                    style="color:#007BFF;">or</h2>



                                <div class="col-md-6 mb-3 position-relative input-clear-wrapper">
                                    <label for="month">Select Month <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="month"
                                           name="month"
                                           class="form-control"
                                           placeholder="Select Month"
                                           autocomplete="off">
                                    <button type="button"
                                            class="clear-input pb-4"
                                            id="clear-month"
                                            title="Clear Month"
                                            style="display:none;">&times;</button>
                                </div>
                            </div>


                        </div>

                        <div class="container mt-4 d-flex justify-content-center">
                            <div class="col-md-3 mb-3">
                                <label for="division_id">Select Division</label>
                                <select id="division_id"
                                        name="division_id"
                                        class="form-control">
                                    <option value="">-- Choose Division --</option>
                                    {{-- Loop divisions here --}}
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division['id'] }}">{{ $division['division_name_en'] }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="col-md-3 mb-3">
                                <label for="district_id">Select District</label>
                                <select id="district_id"
                                        name="district_id"
                                        class="form-control">
                                    <option value="">-- Choose District</option>
                                    {{-- Loop departments here --}}
                                    @foreach ($districts as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['district_name_en'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3 ">
                                <label for="upazila_id">Select Upazila</label>
                                <select id="upazila_id"
                                        name="upazila_id"
                                        class="form-control">
                                    <option value="">-- Choose Upazila --</option>
                                    {{-- Loop sections here --}}
                                    @foreach ($upazilas as $upazila)
                                        <option value="{{ $upazila['id'] }}">{{ $upazila['upazila_name_en'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="group_id">Select Group</label>
                                <select id="group_id"
                                        name="group_id"
                                        class="form-control">
                                    <option value="">-- Choose Group --</option>
                                    {{-- Loop groups here --}}
                                    @foreach ($groups as $group)
                                        <option value="{{ $group['id'] }}">{{ $group['group_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center w-100 mt-4 gap-2">
                            <button type="submit"
                                    id="save-btn"
                                    class="btn btn-sm btn-primary mr-2">Generate Attendance</button>

                            <button type="button"
                                    id="clear-btn"
                                    class="btn btn-sm btn-secondary">Clear</button>
                        </div>
                    </form>

                    <div id="response-message"
                         class="mt-3"
                         style="min-height: 30px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- Bootstrap Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Moment.js -->
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <!-- Daterangepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        var myJQ = $.noConflict(true);
        myJQ(function() {
            myJQ('form').on('submit', function(e) {
                let dateRange = myJQ('#date_range').val().trim();
                let month = myJQ('#month').val().trim();

                if (!dateRange && !month) {
                    e.preventDefault(); // Stop the form from submitting
                    alert("Please select either a Date Range or a Month.");
                    return false;
                }

                // Your existing removal of unnecessary select names
                if (myJQ("select[name='upazila_id']").val()) {
                    myJQ("select[name='district_id']").removeAttr('name');
                    myJQ("select[name='division_id']").removeAttr('name');
                }
                if (myJQ("select[name='district_id']").val()) {
                    myJQ("select[name='division_id']").removeAttr('name');
                }
            });

            // partisal division,district and upazila selection.

            $(document).ready(function() {

                // Initially clear and disable district + upazila
                let $district = $("select[name='district_id']").empty()
                    .append('<option value="">Select District</option>')
                    .prop('disabled', true);

                let $upazila = $("select[name='upazila_id']").empty()
                    .append('<option value="">Select Upazila</option>')
                    .prop('disabled', true);

                // Division change -> load departments
                $("select[name='division_id']").on("change", function() {
                    let divisionId = $(this).val();

                    $district.empty().append('<option value="">Select District</option>').prop(
                        'disabled', true);
                    $upazila.empty().append('<option value="">Select Upazila</option>').prop(
                        'disabled', true);

                    if (divisionId) {
                        $.ajax({
                            url: '/get-districts/' + divisionId,
                            type: 'GET',
                           
                            success: function(data) {
                                $.each(data, function(key, value) {
                                    $district.append('<option value="' + value
                                        .id + '">' + value.district_name_en +
                                        '</option>');
                                });
                                $district.prop('disabled', false);
                            },
                            error: function(xhr) {
                                console.error('ajax error', xhr);
                            }
                        });
                    }
                });

                // District change -> load sections
                $district.on('change', function() {
                    let districtId = $(this).val();

                    $upazila.empty().append('<option value="">Select Upazila</option>').prop(
                        'disabled', true);

                    if (districtId) {
                        $.ajax({
                            url: '/get-upazilas/' + districtId,
                            type: 'GET',
                           
                            success: function(data) {
                                $.each(data, function(key, value) {
                                    $php.append('<option value="' + value
                                        .id + '">' + value.upazila_name_en +
                                        '</option>');
                                });
                                $upazila.prop('disabled', false);
                            }
                        });
                    }
                });

            });

            // -- End of division wise district and upazila selection --

            // Initialize inputs empty and enabled
            myJQ('#date_range').val('').prop('disabled', false);
            myJQ('#month').val('').prop('disabled', false);

            myJQ('#date_range').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                autoUpdateInput: false // important for manual clearing detection
            });

            myJQ('#month').daterangepicker({
                locale: {
                    format: 'YYYY-MM'
                },
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false
            });

            // When date_range selected
            myJQ('#date_range').on('apply.daterangepicker', function(ev, picker) {
                myJQ(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
                if (myJQ(this).val()) {
                    // Clear and disable month
                    myJQ('#month').val('').prop('disabled', true);
                } else {
                    myJQ('#month').prop('disabled', false);
                }
            });

            // When date_range cleared manually
            myJQ('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                myJQ(this).val('');
                myJQ('#month').prop('disabled', false);
            });

            // When month selected
            myJQ('#month').on('apply.daterangepicker', function(ev, picker) {
                myJQ(this).val(picker.startDate.format('YYYY-MM'));
                if (myJQ(this).val()) {
                    // Clear and disable date_range
                    myJQ('#date_range').val('').prop('disabled', true);
                } else {
                    myJQ('#date_range').prop('disabled', false);
                }
            });

            // When month cleared manually
            myJQ('#month').on('cancel.daterangepicker', function(ev, picker) {
                myJQ(this).val('');
                myJQ('#date_range').prop('disabled', false);
            });

            // Clear button resets everything
            myJQ('#clear-btn').on('click', function() {
                myJQ('#date_range').val('').prop('disabled', false);
                myJQ('#month').val('').prop('disabled', false);

                myJQ('#shift_id').val('').prop('disabled', false);
                myJQ('#division_id').val('').prop('disabled', false);
                myJQ('#district_id').val('').prop('disabled', false);
                myJQ('#upazila_id').val('').prop('disabled', false);
                myJQ('#group_id').val('').prop('disabled', false);
            });

            function toggleClearButton(inputSelector, clearBtnSelector) {
                if (myJQ(inputSelector).val()) {
                    myJQ(clearBtnSelector).show();
                } else {
                    myJQ(clearBtnSelector).hide();
                }
            }

            // Initially toggle clear buttons on page load
            toggleClearButton('#date_range', '#clear-date_range');
            toggleClearButton('#month', '#clear-month');

            // On input value change (typing or via picker apply/cancel)
            myJQ('#date_range').on('apply.daterangepicker cancel.daterangepicker input', function() {
                toggleClearButton('#date_range', '#clear-date_range');
            });
            myJQ('#month').on('apply.daterangepicker cancel.daterangepicker input', function() {
                toggleClearButton('#month', '#clear-month');
            });

            // Clear button click handlers
            myJQ('#clear-date_range').on('click', function() {
                myJQ('#date_range').val('').prop('disabled', false).trigger('change');
                myJQ('#month').prop('disabled', false);
                toggleClearButton('#date_range', '#clear-date_range');
            });

            myJQ('#clear-month').on('click', function() {
                myJQ('#month').val('').prop('disabled', false).trigger('change');
                myJQ('#date_range').prop('disabled', false);
                toggleClearButton('#month', '#clear-month');
            });
            // When date_range selected
            myJQ('#date_range').on('apply.daterangepicker', function(ev, picker) {
                myJQ(this).val(
                    picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD')
                );
                toggleClearButton('#date_range', '#clear-date_range'); // ADD THIS
                if (myJQ(this).val()) {
                    myJQ('#month').val('').prop('disabled', true);
                    toggleClearButton('#month', '#clear-month'); // ADD THIS
                } else {
                    myJQ('#month').prop('disabled', false);
                }
            });

            // When date_range cleared manually
            myJQ('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                myJQ(this).val('');
                toggleClearButton('#date_range', '#clear-date_range'); // ADD THIS
                myJQ('#month').prop('disabled', false);
            });

            // When month selected
            myJQ('#month').on('apply.daterangepicker', function(ev, picker) {
                myJQ(this).val(picker.startDate.format('YYYY-MM'));
                toggleClearButton('#month', '#clear-month'); // ADD THIS
                if (myJQ(this).val()) {
                    myJQ('#date_range').val('').prop('disabled', true);
                    toggleClearButton('#date_range', '#clear-date_range'); // ADD THIS
                } else {
                    myJQ('#date_range').prop('disabled', false);
                }
            });

            // When month cleared manually
            myJQ('#month').on('cancel.daterangepicker', function(ev, picker) {
                myJQ(this).val('');
                toggleClearButton('#month', '#clear-month'); // ADD THIS
                myJQ('#date_range').prop('disabled', false);
            });


        });
    </script>
@endpush
