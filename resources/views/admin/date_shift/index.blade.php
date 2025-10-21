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
                    <form id="sync-form"
                          action="{{ route('attendance.report') }}"
                          method="GET"
                          class="d-flex flex-column align-items-center"
                          style="width: 100%;">

                        @csrf
                        <div class="container mt-4 d-flex justify-content-center ">
                            <!-- Branch Selection -->
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="branch_uid">Select Branch <span class="text-danger">*</span></label>
                                    <select id="branch_uid"
                                            name="branch_uid"
                                            class="form-control select2bs4"
                                            style="width: 100%;"
                                            required>
                                        <option value="">-- Choose Branch --</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch['branch_uid'] }}">{{ $branch['branch_name_en'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Shift Selection (populated based on selected branch) -->
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="shift_uid">Select Shift <span class="text-danger">*</span></label>
                                    <select id="shift_uid"
                                            name="shift_uid"
                                            class="form-control select2bs4"
                                            style="width: 100%;"
                                            required
                                            disabled>
                                        <option value="">-- Choose Shift --</option>
                                        <!-- Shifts will be populated dynamically via JavaScript -->
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3 d-flex justify-content-center"
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
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division['id'] }}">{{ $division['division_name_en'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="district_id">Select District</label>
                                <select id="district_id"
                                        name="district_id"
                                        class="form-control"
                                        disabled>
                                    <option value="">-- Choose District</option>
                                    <!-- Districts will be populated via AJAX based on division -->
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="upazila_id">Select Upazila</label>
                                <select id="upazila_id"
                                        name="upazila_id"
                                        class="form-control"
                                        disabled>
                                    <option value="">-- Choose Upazila --</option>
                                    <!-- Upazilas will be populated via AJAX based on district -->
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="group_id">Select Group</label>
                                <select id="group_id"
                                        name="group_id"
                                        class="form-control">
                                    <option value="">-- Choose Group --</option>
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
        // Store data from PHP (no AJAX needed for branches/shifts since we have all data)
        const shiftsData = @json($shifts);
        const districtsData = @json($districts);
        const upazilasData = @json($upazilas);

        var myJQ = $.noConflict(true);

        myJQ(function() {
            // ========== BRANCH & SHIFTS (No AJAX - all data loaded) ==========
            myJQ('#branch_uid').on('change', function() {
                const branchId = myJQ(this).val();
                const shiftSelect = myJQ('#shift_uid');

                console.log("hi3");
                console.log(shiftsData);

                shiftSelect.empty().append('<option value="">-- Choose Shift --</option>');

                if (branchId) {
                    // Filter shifts by selected branch from pre-loaded data
                    const branchShifts = shiftsData.filter(shift => shift.branch_uid == branchId);

                    if (branchShifts.length > 0) {
                        branchShifts.forEach(shift => {
                            console.log("hi5");
                            console.log(shift);
                            shiftSelect.append(
                                myJQ('<option>', {   //p-1: just fix this below codes of this funtion ? 
                                    value: shift.shift_uid,
                                    text: shift.shift_name_en + ' (' + shift
                                    .shift_start_time + ' - ' + shift.shift_end_time +')'

                                 
                                })

                            );
                        });
                        shiftSelect.prop('disabled', false);
                    } else {
                        shiftSelect.append('<option value="">No shifts available</option>');
                        shiftSelect.prop('disabled', true);
                    }
                } else {
                    shiftSelect.prop('disabled', true);
                }
            });

            // ========== DIVISION, DISTRICT & UPAZILA (Using pre-loaded data) ==========
            myJQ('#division_id').on('change', function() {
                const divisionId = myJQ(this).val();
                const districtSelect = myJQ('#district_id');
                const upazilaSelect = myJQ('#upazila_id');

                districtSelect.empty().append('<option value="">-- Choose District --</option>').prop(
                    'disabled', true);
                upazilaSelect.empty().append('<option value="">-- Choose Upazila --</option>').prop(
                    'disabled', true);

                if (divisionId) {
                    // Filter districts by division from pre-loaded data
                    const divisionDistricts = districtsData.filter(district => district.division_id ==
                        divisionId);

                    if (divisionDistricts.length > 0) {
                        divisionDistricts.forEach(district => {
                            districtSelect.append(
                                myJQ('<option>', {
                                    value: district.id,
                                    text: district.district_name_en
                                })
                            );
                        });
                        districtSelect.prop('disabled', false);
                    } else {
                        districtSelect.append('<option value="">No districts available</option>');
                        districtSelect.prop('disabled', true);
                    }
                }
            });

            myJQ('#district_id').on('change', function() {
                const districtId = myJQ(this).val();
                const upazilaSelect = myJQ('#upazila_id');

                upazilaSelect.empty().append('<option value="">-- Choose Upazila --</option>').prop(
                    'disabled', true);

                if (districtId) {
                    // Filter upazilas by district from pre-loaded data
                    const districtUpazilas = upazilasData.filter(upazila => upazila.district_id ==
                        districtId);

                    if (districtUpazilas.length > 0) {
                        districtUpazilas.forEach(upazila => {
                            upazilaSelect.append(
                                myJQ('<option>', {
                                    value: upazila.id,
                                    text: upazila.upazila_name_en
                                })
                            );
                        });
                        upazilaSelect.prop('disabled', false);
                    } else {
                        upazilaSelect.append('<option value="">No upazilas available</option>');
                        upazilaSelect.prop('disabled', true);
                    }
                }
            });

            // ========== FORM VALIDATION ==========
            myJQ('form').on('submit', function(e) {
                let dateRange = myJQ('#date_range').val().trim();
                let month = myJQ('#month').val().trim();

                if (!dateRange && !month) {
                    e.preventDefault();
                    alert("Please select either a Date Range or a Month.");
                    return false;
                }

                // Remove unnecessary parameters from form submission
                if (myJQ("select[name='upazila_id']").val()) {
                    myJQ("select[name='district_id']").removeAttr('name');
                    myJQ("select[name='division_id']").removeAttr('name');
                } else if (myJQ("select[name='district_id']").val()) {
                    myJQ("select[name='division_id']").removeAttr('name');
                }
            });

            // ========== DATE RANGE & MONTH PICKERS ==========
            myJQ('#date_range').val('').prop('disabled', false);
            myJQ('#month').val('').prop('disabled', false);

            myJQ('#date_range').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                autoUpdateInput: false
            });

            myJQ('#month').daterangepicker({
                locale: {
                    format: 'YYYY-MM'
                },
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false
            });

            // Date range events
            myJQ('#date_range').on('apply.daterangepicker', function(ev, picker) {
                myJQ(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
                if (myJQ(this).val()) {
                    myJQ('#month').val('').prop('disabled', true);
                } else {
                    myJQ('#month').prop('disabled', false);
                }
                toggleClearButton('#date_range', '#clear-date_range');
            });

            myJQ('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                myJQ(this).val('');
                myJQ('#month').prop('disabled', false);
                toggleClearButton('#date_range', '#clear-date_range');
            });

            // Month events
            myJQ('#month').on('apply.daterangepicker', function(ev, picker) {
                myJQ(this).val(picker.startDate.format('YYYY-MM'));
                if (myJQ(this).val()) {
                    myJQ('#date_range').val('').prop('disabled', true);
                } else {
                    myJQ('#date_range').prop('disabled', false);
                }
                toggleClearButton('#month', '#clear-month');
            });

            myJQ('#month').on('cancel.daterangepicker', function(ev, picker) {
                myJQ(this).val('');
                myJQ('#date_range').prop('disabled', false);
                toggleClearButton('#month', '#clear-month');
            });

            // ========== CLEAR BUTTON FUNCTIONALITY ==========
            myJQ('#clear-btn').on('click', function() {
                // Clear all form fields
                myJQ('#date_range').val('').prop('disabled', false);
                myJQ('#month').val('').prop('disabled', false);
                myJQ('#branch_uid').val('').trigger('change');
                myJQ('#division_id').val('').trigger('change');
                myJQ('#group_id').val('');

                // Reset dropdowns
                myJQ('#shift_uid').empty().append('<option value="">-- Choose Shift --</option>').prop(
                    'disabled', true);
                myJQ('#district_id').empty().append('<option value="">-- Choose District --</option>').prop(
                    'disabled', true);
                myJQ('#upazila_id').empty().append('<option value="">-- Choose Upazila --</option>').prop(
                    'disabled', true);

                toggleClearButton('#date_range', '#clear-date_range');
                toggleClearButton('#month', '#clear-month');
            });

            // ========== HELPER FUNCTIONS ==========
            function toggleClearButton(inputSelector, clearBtnSelector) {
                if (myJQ(inputSelector).val()) {
                    myJQ(clearBtnSelector).show();
                } else {
                    myJQ(clearBtnSelector).hide();
                }
            }

            // Initialize clear buttons
            toggleClearButton('#date_range', '#clear-date_range');
            toggleClearButton('#month', '#clear-month');

            // Input change events for clear buttons
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
        });
    </script>
@endpush
