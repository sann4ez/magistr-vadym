@php
    $n = count($type['fields'])
@endphp
<input type="hidden" name="type" value="{{ $type['key'] }}">
<div class="modal-body">

    <div class="row">
        @foreach($type['fields'] as $name => $field)
            @php
                if (!(($field['readonly'] ?? false) && isset($itemUpdate))) {
                    unset($field['readonly']);
                }

                if (isset($itemUpdate)) {
                    $field['value'] = $itemUpdate->{$field['name']};
                    $field['selected'] = $itemUpdate->{$field['name']}; //todo
                    $field['path'] = $itemUpdate->{$field['name']}; //todo
                }
            @endphp

            <div class="@if($n > 1) col-md-6 @else col-md-12 @endif">
                {!! Lte3::field($field) !!}
            </div>
        @endforeach
    </div>

</div>
