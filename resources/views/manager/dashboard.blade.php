<!-- resources/views/manager/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        
                <div class="card-body">
                    <h4 class="mb-4">Welcome  {{ Auth::user()->name }}!</h4>
                   
                </div>
        
    </div>
</div>
@endsection