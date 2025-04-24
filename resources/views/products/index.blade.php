@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        @if(auth()->user()->role === 'admin')
            @include('admin.partials.admin-sidebar')
        @elseif(auth()->user()->role === 'gestionnaire')
        @include('gestionnaire.partials.sidebar_gestionnaire')
        @else
            @include('magasin.partials.sidebar')
        @endif
        <div class="col">
            <h2>Products</h2>
        </div>
        
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection