@extends('admin.master')

@php
    $title = $upazila ? 'Edit Upazila' : 'Add Upazila';
    $header = $title;
@endphp

@section('admin_content')
    <div class="card">
        <div class="card-header">
            <h5>{{ $header }}</h5>
        </div>
        <div class="card-body">
            <form id="upazilaForm"
                  action="{{ $upazila ? route('upazila.update', $upazila->id) : route('upazila.store') }}"
                  method="POST">
                @csrf
                @if ($upazila)
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="upazila_name_en">Upazila Name (English) <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="upazila_name_en"
                                   name="upazila_name_en"
                                   class="form-control @error('upazila_name_en') is-invalid @enderror"
                                   value="{{ old('upazila_name_en', $upazila->upazila_name_en ?? '') }}"
                                   required
                                   placeholder="Enter upazila name in English">
                            @error('upazila_name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="upazila_name_bn">Upazila Name (Bangla)</label>
                            <input type="text"
                                   id="upazila_name_bn"
                                   name="upazila_name_bn"
                                   class="form-control @error('upazila_name_bn') is-invalid @enderror"
                                   value="{{ old('upazila_name_bn', $upazila->upazila_name_bn ?? '') }}"
                                   placeholder="Enter upazila name in Bangla">
                            @error('upazila_name_bn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="district_id">Select District <span class="text-danger">*</span></label>
                        <select name="district_id"
                                id="district_id"
                                class="form-control @error('district_id') is-invalid @enderror"
                                required>
                            <option value="">-- Select District --</option>
                            @foreach ($districts as $dist)
                                <option value="{{ $dist->id }}"
                                        {{ old('district_id', $upazila->district_id ?? '') == $dist->id ? 'selected' : '' }}>
                                    {{ $dist->district_name_en ?? $dist->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Status Field (Optional) -->


                <div class="form-group mt-4">
                    <button type="submit"
                            class="btn btn-success">
                        <i class="fas fa-save"></i> {{ $upazila ? 'Update' : 'Save' }}
                    </button>
                    <a href="{{ route('upazila.list') }}"
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @if (session('success'))
        <script>
            toastr.success('{{ session('success') }}');
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation
            const form = document.getElementById('upazilaForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const upazilaNameEn = document.getElementById('upazila_name_en').value.trim();
                    const district = document.getElementById('district_id').value;

                    if (!upazilaNameEn || !district) {
                        e.preventDefault();
                        toastr.error('Please fill in all required fields');
                        return false;
                    }
                });
            }

            // Optional: Add dynamic district loading based on division
            // If you have divisions and want to load districts based on division selection
            const divisionSelect = document.getElementById('division_id');
            if (divisionSelect) {
                divisionSelect.addEventListener('change', function() {
                    const divisionId = this.value;
                    const districtSelect = document.getElementById('district_id');

                    if (divisionId) {
                        // Clear existing options except the first one
                        districtSelect.innerHTML = '<option value="">-- Select District --</option>';

                        // Fetch districts via AJAX
                        fetch(`/api/districts/${divisionId}`)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(district => {
                                    const option = document.createElement('option');
                                    option.value = district.id;
                                    option.textContent = district.district_name_en;
                                    districtSelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            }
        });
    </script>
@endsection
