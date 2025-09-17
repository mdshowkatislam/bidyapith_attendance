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
                                        <option value="{{ $division['id'] }}">{{ $division['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="department_id">Select Department</label>
                                <select id="department_id"
                                        name="department_id"
                                        class="form-control">
                                    <option value="">-- Choose Department</option>
                                    {{-- Loop departments here --}}
                                    @foreach ($departments as $department)
                                        <option value="{{ $department['id'] }}">{{ $department['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3 ">
                                <label for="section_id">Select Section</label>
                                <select id="section_id"
                                        name="section_id"
                                        class="form-control">
                                    <option value="">-- Choose Section --</option>
                                    {{-- Loop sections here --}}
                                    @foreach ($sections as $section)
                                        <option value="{{ $section['id'] }}">{{ $section['name'] }}</option>
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
                if (myJQ("select[name='section_id']").val()) {
                    myJQ("select[name='department_id']").removeAttr('name');
                    myJQ("select[name='division_id']").removeAttr('name');
                }
                if (myJQ("select[name='department_id']").val()) {
                    myJQ("select[name='division_id']").removeAttr('name');
                }
            });

            // partisal division,department and section selection.

            $(document).ready(function() {

                // Initially clear and disable department + section
                let $department = $("select[name='department_id']").empty()
                    .append('<option value="">Select Department</option>')
                    .prop('disabled', true);

                let $section = $("select[name='section_id']").empty()
                    .append('<option value="">Select Section</option>')
                    .prop('disabled', true);

                // Division change -> load departments
                $("select[name='division_id']").on("change", function() {
                    let divisionId = $(this).val();

                    $department.empty().append('<option value="">Select Department</option>').prop(
                        'disabled', true);
                    $section.empty().append('<option value="">Select Section</option>').prop(
                        'disabled', true);

                    if (divisionId) {
                        $.ajax({
                            url: '{{ route('get.departments') }}',
                            type: 'GET',
                            data: {
                                division_id: divisionId
                            },
                            success: function(data) {
                                $.each(data, function(key, value) {
                                    $department.append('<option value="' + value
                                        .id + '">' + value.name +
                                        '</option>');
                                });
                                $department.prop('disabled', false);
                            },
                            error: function(xhr) {
                                console.error('ajax error', xhr);
                            }
                        });
                    }
                });

                // Department change -> load sections
                $department.on('change', function() {
                    let departmentId = $(this).val();

                    $section.empty().append('<option value="">Select Section</option>').prop(
                        'disabled', true);

                    if (departmentId) {
                        $.ajax({
                            url: '{{ route('get.sections') }}',
                            type: 'GET',
                            data: {
                                department_id: departmentId
                            },
                            success: function(data) {
                                $.each(data, function(key, value) {
                                    $section.append('<option value="' + value
                                        .id + '">' + value.name +
                                        '</option>');
                                });
                                $section.prop('disabled', false);
                            }
                        });
                    }
                });

            });

            // -- End of division wise department and section selection --

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
                myJQ('#department_id').val('').prop('disabled', false);
                myJQ('#section_id').val('').prop('disabled', false);
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
