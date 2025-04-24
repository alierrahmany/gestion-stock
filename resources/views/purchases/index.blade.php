@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @if(auth()->user()->role === 'admin')
        @include('admin.partials.admin-sidebar')
        @else
            @include('gestionnaire.partials.sidebar_gestionnaire')
        @endif
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2">Purchases</h1>
                
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Supplier</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Date</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->id }}</td>
                                    <td>{{ $purchase->product->name }}</td>
                                    <td>{{ $purchase->supplier->name }}</td>
                                    <td>{{ $purchase->quantity }}</td>
                                    <td>{{ number_format($purchase->total_price, 2) }} DH</td>
                                    <td>{{ $purchase->purchase_date }}</td>
                                   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection