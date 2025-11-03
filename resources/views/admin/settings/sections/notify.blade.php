@php($varGroup = \Domain::getGroup())
@php(\Variable::setGroup($varGroup))

<div class="card card-solid">
    <div class="card-header with-border">
        <h3 class="card-title">Загальне</h3>
    </div>
    {!! Lte3::formOpen(['action' => route('admin.settings.save'), 'model' => null, 'method' => 'POST']) !!}
    {!! Lte3::hidden('group', $varGroup) !!}
    <div class="card-body">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Email з OTP-кодом</h3>
            </div>
            <div class="card-body">
                {!! Lte3::text('vars_array[notify][client][UserAuthOtp][email][subject]', \Variable::getArray('notify.client.UserAuthOtp.email.subject', ''), [
                    'label' => 'Тема',
                ]) !!}

                {!! Lte3::textarea('vars_array[notify][client][UserAuthOtp][email][body]', \Variable::getArray('notify.client.UserAuthOtp.email.body', ''), [
                    'label' => 'Текст (HTML)',
                    'rows' => 8
                ]) !!}

                @include('admin.parts.str-tokens-callout', ['tokens' => \App\Models\User::tokensList(), 'height' => 0])

            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="text-right">
            {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
        </div>
    </div>

    {!! Lte3::formClose() !!}
</div>
