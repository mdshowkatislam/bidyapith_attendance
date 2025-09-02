@csrf

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="profile_id">Profile ID <span class="text-danger">*</span></label>
            <input type="number"
                   name="profile_id"
                   class="form-control"
                    value="{{ old('name', $employee->profile_id ?? '') }}"
                   required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="name">Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name', $employee->name ?? '') }}"
                   required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="father_name">Father's Name</label>
            <input type="text"
                   name="father_name"
                   class="form-control"
                   value="{{ old('father_name', $employee->father_name ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="mother_name">Mother's Name</label>
            <input type="text"
                   name="mother_name"
                   class="form-control"
                   value="{{ old('mother_name', $employee->mother_name ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date"
                   name="dob"
                   class="form-control"
                   value="{{ old('dob', $employee->dob ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="joining_date">Joining Date</label>
            <input type="date"
                   name="joining_date"
                   class="form-control"
                   value="{{ old('joining_date', $employee->joining_date ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="nid">NID</label>
            <input type="text"
                   name="nid"
                   class="form-control"
                   value="{{ old('nid', $employee->nid ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="mobile_number">Mobile Number</label>
            <input type="text"
                   name="mobile_number"
                   class="form-control"
                   value="{{ old('mobile_number', $employee->mobile_number ?? '') }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="present_address">Present Address</label>
            <textarea name="present_address"
                      class="form-control">{{ old('present_address', $employee->present_address ?? '') }}</textarea>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="permanent_address">Permanent Address</label>
            <textarea name="permanent_address"
                      class="form-control">{{ old('permanent_address', $employee->permanent_address ?? '') }}</textarea>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="division_id">Division <span class="text-danger">*</span></label>
            <select name="division_id"
                    class="form-control"
                    required>
                <option value="">Select Division</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->id }}"
                            {{ old('division_id', $employee->division_id ?? '') == $division->id ? 'selected' : '' }}>
                        {{ $division->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="department_id">Department <span class="text-danger">*</span></label>
            <select name="department_id"
                    class="form-control"
                    required>
                <option value="">Select Department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}"
                            {{ old('department_id', $employee->department_id ?? '') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="section_id">Section</label>
            <select name="section_id"
                    class="form-control"
                    required>
                <option value="">Select Section <span class="text-danger">*</span></option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}"
                            {{ old('section_id', $employee->section_id ?? '') == $section->id ? 'selected' : '' }}>
                        {{ $section->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="badgenumber">Badge Number</label>
            <input type="text"
                   name="badgenumber"
                   class="form-control"
                   value="{{ old('badgenumber', $employee->badgenumber ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="company_id">Company ID</label>
            <input type="text"
                   name="company_id"
                   class="form-control"
                   value="{{ old('company_id', $employee->company_id ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="card_number">Card Number</label>
            <input type="text"
                   name="card_number"
                   class="form-control"
                   value="{{ old('card_number', $employee->card_number ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status"
                    class="form-control"
                    required>
                <option value="1"
                        {{ old('status', $employee->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                <option value="0"
                        {{ old('status', $employee->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="picture">Picture</label>
            <input type="file"
                   name="picture"
                   class="form-control-file">
            @if (isset($employee) && $employee->picture)
                <img src="{{ asset('storage/' . $employee->picture) }}"
                     alt="Employee Picture"
                     height="80"
                     class="mt-2">
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $(document).ready(function() {
        // Initially disable department and section
        $("select[name='department_id']").prop('disabled', true);
        $("select[name='section_id']").prop('disabled', true);

        $("select[name='division_id']").on("change", function() {
            let divisionId = $(this).val();

            if (divisionId) {
                $.ajax({
                    url: '{{ route('get.departments') }}',
                    type: 'GET',
                    data: {
                        division_id: divisionId
                    },
                    success: function(data) {
                        let $dept = $("select[name='department_id']");
                        let $sec = $("select[name='section_id']");

                        $dept.empty().append('<option value="">Select Department</option>');
                        $sec.empty().append('<option value="">Select Section</option>')
                            .prop('disabled', true);

                        $.each(data, function(_, value) {
                            $dept.append('<option value="' + value.id + '">' + value
                                .name + '</option>');
                        });

                        $dept.prop('disabled', false); // Enable after loading
                    }
                });
            } else {
                $("select[name='department_id']").empty().append(
                    '<option value="">Select Department</option>').prop('disabled', true);
                $("select[name='section_id']").empty().append(
                    '<option value="">Select Section</option>').prop('disabled', true);
            }
        });

        $('select[name="department_id"]').on('change', function() {
            var departmentId = $(this).val();

            if (departmentId) {
                $.ajax({
                    url: '{{ route('get.sections') }}',
                    type: 'GET',
                    data: {
                        department_id: departmentId
                    },
                    success: function(data) {
                        let $sec = $('select[name="section_id"]');
                        $sec.empty().append('<option value="">Select Section</option>');

                        $.each(data, function(_, value) {
                            $sec.append('<option value="' + value.id + '">' + value
                                .name + '</option>');
                        });

                        $sec.prop('disabled', false); // Enable after loading
                    }
                });
            } else {
                $('select[name="section_id"]').empty().append(
                    '<option value="">Select Section</option>').prop('disabled', true);
            }
        });
      
        $('select[name="division_id"], select[name="department_id"], select[name="section_id"]')
            .on('change', function() {
                $(this).data('changed', true);
            });
    });
</script>
