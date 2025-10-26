@extends('admin.parts.filter-wrap2')

@section('body')
    <div class="row">

        <div class="col-md-6">
            {!! Lte3::text('q', null, ['label' => 'Пошук',]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::select2('role', request('role'), \App\Models\User::rolesList('name', 'key'), [
                'label' => 'Роль',
                'empty_value' => '--',
            ]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::select2('status', request('status'), \App\Models\User::statusesList('name', 'key'), [
                'label' => 'Статус',
                'empty_value' => '--',
            ]) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            {!! Lte3::datepicker('created_at_from', null, [
                'label' => 'Створено, від',
                'default' => '',
            ]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::datepicker('created_at_to', null, [
                'label' => 'Створено, до',
                'default' => '',
            ]) !!}
        </div>


        <div class="col-md-3">
            {!! Lte3::datepicker('activity_at_from', null, [
                'label' => 'Активність, від',
                'default' => '',
            ]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::datepicker('activity_at_to', null, [
                'label' => 'Активність, до',
                'default' => '',
            ]) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            {!! Lte3::select2('activity', request('activity'), ['' => '--', 'has' => 'Була активність', 'hasnot' => 'Не було активності'], [
                'label' => 'Активність',
            ]) !!}
        </div>
    </div>

@stop
