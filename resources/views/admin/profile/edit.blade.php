@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => 'Мій профіль',
    ])

    <section class="content">
        <div class="row">

            {{-- КОРИСТУВАЧ --}}
            <div class="col-md-6">
                {!! Lte3::formOpen(['action' => route('admin.profile.update', $user), 'model' => $user, 'method' => 'POST']) !!}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Дані <strong>{{ $user->fullname }}</strong></h3>
                    </div>
                    <div class="card-body ">

                        {!! Lte3::text('name', null, ['label' => 'Ім\'я']) !!}

                        {!! Lte3::email('email', null, ['label' => 'Емейл']) !!}

                        {!! Lte3::datepicker('birthday', null, [
                            'label' => 'Дата народження',
                            'format' => 'Y-m-d',
                            'default' => '',
                        ]) !!}

                        <div class="row">
                            <div class="col-md-6">
                                {!! Lte3::password('password', null, ['label' => 'Пароль'])!!}
                            </div>
                            <div class="col-md-6">
                                {!! Lte3::password('password_confirmation', null, ['label' => 'Підтвердження']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <div class="text-right">
                            {!! Lte3::btnSubmit('Зберегти') !!}
                        </div>
                    </div>
                </div>
                {!! Lte3::formClose() !!}
            </div>


        </div>
    </section>
@stop
