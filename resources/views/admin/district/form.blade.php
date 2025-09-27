@extends('admin.master')

@php
    $title = $district ? 'Edit District' : 'Add District';
    $header = $title;
@endphp

@section('admin_content')
<div class="card">
    <div class="card-header">
        <h5>{{ $header }}</h5>
    </div>
    <div class="card-body">
        <form id="districtForm" action="{{ $district ? route('district.update', $district->id) : route('district.store') }}" method="POST">
            @csrf
            @if($district)
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="district_name_en">District Name (English) <span class="text-danger">*</span></label>
                        <input type="text" id="district_name_en" name="district_name_en" class="form-control @error('district_name_en') is-invalid @enderror" 
                               value="{{ old('district_name_en', $district->district_name_en ?? '') }}" required 
                               placeholder="Enter district name in English">
                        @error('district_name_en') 
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="district_name_bn">District Name (Bengali) </label>
                        <input type="text" id="district_name_bn" name="district_name_bn" class="form-control @error('district_name_bn') is-invalid @enderror" 
                               value="{{ old('district_name_bn', $district->district_name_bn ?? '') }}" 
                               placeholder="Enter district name in Bengali">
                        @error('district_name_bn') 
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
   <div class="col-md-6">
            <div class="form-group">
                <label for="division_id">Select Division <span class="text-danger">*</span></label>
                <select name="division_id" id="division_id" class="form-control @error('division_id') is-invalid @enderror" required>
                    <option value="">-- Select Division --</option>
                    @foreach($divisions as $div)
                        <option value="{{ $div->id }}" 
                            {{ (old('division_id', $district->division_id ?? '') == $div->id) ? 'selected' : '' }}>
                            {{ $div->division_name_en ?? $div->name }}
                        </option>
                    @endforeach
                </select>
                @error('division_id') 
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            </div>

          

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ $district ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('district.list') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@if(session('success'))
    <script>
        toastr.success('{{ session('success') }}');
    </script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    document.getElementById('districtForm').addEventListener('submit', function(e) {
        const districtNameEn = document.getElementById('district_name_en').value.trim();
        const division = document.getElementById('division_id').value;
        
        if (!districtNameEn || !division) {
            e.preventDefault();
            toastr.error('Please fill in all required fields');
        }
    });
});
</script>
@endsection