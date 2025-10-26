<div class="row">
    <div class="col-md-6">
        {!! Lte3::text('name', isset($data) ? \Arr::get($data, 'name') : null, ['label' => 'Ім\'я']) !!}

        {!! Lte3::email('email', isset($data) ? \Arr::get($data, 'email') : null, ['label' => 'Email']) !!}

        {!! Lte3::password('password', null, ['label' => 'Пароль']) !!}
        {!! Lte3::password('password_confirmation', null, ['label' => 'Підтвердження']) !!}

    </div>

    <div class="col-md-6">
{{--

        {!! Lte3::datepicker('birthday', null, [
            'label' => 'Дата народження',
            'format' => 'Y-m-d',
            'default' => '',
        ]) !!}
--}}

        {!! Lte3::select2('status', isset($data) ? \Arr::get($data, 'status') : null, \App\Models\User::statusesList('name', 'key'), ['label' => 'Статус']) !!}

        <div class="callout callout-danger">
            <h5><code><i class="fas fa-exclamation-triangle"></i></code> Ролі </h5>
            {!! Lte3::radiogroup('roles', (isset($user) ? $user->roles->first()?->name : null) ?: \App\Models\Role::DEFAULT_ROLE_CLIENT, \App\Models\Role::all()->pluck('name', 'name')->toArray(), ['label' => '']) !!}
            <p class="small">* Увага! Ролі можуть надати доступ до адмінпанелі!</p>
        </div>

        @push('scripts')
            <script>
                let previousValue = $('input[name="roles"]:checked').val();
                $('input[name="roles"]').on('change', function () {
                    const newValue = $(this).val();
                    const confirmed = confirm(`Ви впевнені, що хочете змінити роль користувача на ${newValue}?`);

                    if (confirmed) {
                        previousValue = newValue;
                    } else {
                        $(`input[name="roles"][value="${previousValue}"]`).prop('checked', true);
                    }
                });
            </script>
        @endpush


    </div>
    <div class="col-md-12">
        {!! Lte3::textarea('comment', isset($data) ? \Arr::get($data, 'comment') : null, ['label' => 'Коментар', 'rows' => 5]) !!}
    </div>
</div>
