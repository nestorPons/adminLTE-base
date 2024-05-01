@section('title', __('Users'))
<div class="">
    @livewire('components.modals', ['model' => $users->items()[0]])
    @livewire('components.lists', [
        'section' => 'User',
        'icon' => 'fa-users',
        'attr'=>['id','name','email'],
        'data' => $users->items(),
        'buttons' => ['delete', 'edit']
    ])
</div>