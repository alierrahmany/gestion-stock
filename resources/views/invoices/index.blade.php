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
            <h2>Invoices</h2>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Amount</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $invoice->date->format('d/m/Y') }}</td>
                            <td>{{ $invoice->client->name }}</td>
                            <td>{{ number_format($invoice->qty * $invoice->price, 2) }}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection