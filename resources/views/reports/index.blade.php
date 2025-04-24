@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        @if(auth()->user()->role === 'admin')
        @include('admin.partials.admin-sidebar')
        @else
            @include('magasin.partials.sidebar')
        @endif
        
        <div class="col">
            <h2>Reports</h2>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('reports.generate') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="report_type" class="form-label">Report Type</label>
                        <select class="form-select" id="report_type" name="report_type" required>
                            <option value="">Select Report Type</option>
                            <option value="sales">Sales Report</option>
                            <option value="products">Products Report</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-file-earmark-text"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection