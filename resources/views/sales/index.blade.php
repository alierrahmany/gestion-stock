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
            <h2>Sales</h2>
        </div>
       
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Client</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr>
                            <td>{{ $sale->date->format('d/m/Y') }}</td>
                            <td>{{ $sale->product->name }}</td>
                            <td>{{ $sale->client->name }}</td>
                            <td>{{ $sale->qty }}</td>
                            <td>{{ number_format($sale->price, 2) }}</td>
                            <td>{{ number_format($sale->qty * $sale->price, 2) }}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>
@endsection