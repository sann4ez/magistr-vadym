<div class="row">
    <div class="col-md-6">
        {!! Lte3::text('name', isset($data) ? \Arr::get($data, 'name') : null, ['label' => 'Ім\'я']) !!}

        {!! Lte3::text('lastname', isset($data) ? \Arr::get($data, 'lastname') : null, ['label' => 'Прізвище']) !!}

        {!! Lte3::email('email', isset($data) ? \Arr::get($data, 'email') : null, ['label' => 'Email']) !!}

        {!! Lte3::text('phone', isset($data) ? \Arr::get($data, 'phone') : null, ['label' => 'Телефон']) !!}

        {!! Lte3::password('password', null, ['label' => 'Пароль']) !!}
        {!! Lte3::password('password_confirmation', null, ['label' => 'Підтвердження']) !!}
    </div>

    <div class="col-md-6">
        {!! Lte3::select2('status', isset($data) ? \Arr::get($data, 'status') : null, \App\Models\User::statusesList('name', 'key'), ['label' => 'Статус']) !!}

        {!! Lte3::textarea('comment', isset($data) ? \Arr::get($data, 'comment') : null, ['label' => 'Коментар', 'rows' => 5]) !!}
    </div>
</div>
