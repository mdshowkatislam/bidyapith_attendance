@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<h4 id="formTitle">Add Working Day</h4>
<form id="workDayForm" data-action="create">
    @csrf
    <input type="hidden" name="id">

    <div class="form-group mb-2">
        <label for="day_name">Day Name</label>
        <input type="text" class="form-control" name="day_name" required>
        <small id="day_name_error" class="text-danger"></small>
    </div>

    <div class="form-group mb-3">
        <label>
            <input type="checkbox" name="is_weekend"> Is Weekend
        </label>
        <br>
        <small id="is_weekend_error" class="text-danger"></small>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

