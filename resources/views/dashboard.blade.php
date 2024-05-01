<x-app-layout>
    @if (session()->has('message'))
        <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;">
            {{ session('message') }}
        </div>
    @endif
    @section('content')
        <div class="my-2">
            @include('livewire.users.index')
        </div>
    @endsection
</x-app-layout>
