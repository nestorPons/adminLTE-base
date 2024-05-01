<div>
    <div class="card-header">
        <div class="d-flex align-items-baseline justify-content-between">
            <div class="float-left">
                <h4><i class="fa {{ $icon }}  text-info"></i>
                    {{ __($section . ' Listing') }}
                </h4>
            </div>

            <div>
                <input wire:model.live='keyWord' type="text" class="form-control" name="search" id="search" placeholder="{{ __('Search '. $section) }}">
            </div>
            <div class="btn btn-sm btn-info" data-toggle="modal" data-target="#createDataModal">
                <i class="fa fa-plus"></i> {{ __('Add ' . $section) }}
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="height: 80vh">
            <table class="table table-bordered table-sm">
                <thead class="thead">
                    <tr>
                        @foreach($attrs as $row)
                        <td>{{ ucwords(__($row)) }}</td>
                        @endforeach
                        <td>{{ __('ACTIONS') }}</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                    <tr>
                        @foreach($attrs as $attr)
                        <td>{{ $row->{$attr} }}</td>
                        @endforeach
                        <td class="actions-buttons">
                            @if (in_array('edit', $buttons))
                            <x-buttons.modal :model="json_encode(get_class($row))" :id="$row->id" target="#dataModal" />
                            @endif
                            @if (in_array('delete', $buttons))
                            <x-buttons.delete function="" />
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="100%">{{ __('No data Found') }} </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="float-end"></div>
        </div>
    </div>
</div>