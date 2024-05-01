@extends('components.layouts.dashboard')
@section('content')
<div class="container-fluid p-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @livewire('users.profile')
        </div>
    </div>
</div>
@endsection
