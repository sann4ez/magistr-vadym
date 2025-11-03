@php($varGroup = \Domain::getId())
@php(\Variable::setGroup($varGroup))

<div class="card card-solid">
    <div class="card-header with-border">
        <h3 class="card-title">Вигляд</h3>
    </div>
    {!! Lte3::formOpen(['action' => route('admin.settings.save'), 'model' => null, 'method' => 'POST']) !!}
    {!! Lte3::hidden('group', $varGroup) !!}
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Основне</h3>
                    </div>
                    <div class="card-body">
                        {!! Lte3::text('vars_array[appearance][phone][mask]', \Variable::getArray('appearance.phone.mask', ''), ['label' => 'Маска телефону', 'placeholder' => '+7 (###) ### ## ##']) !!}
                        {{--
                        {!! Lte3::colorpicker('vars_array[appearance][colors][main]', \Variable::getArray('appearance.colors.main', ''), ['label' => 'Основний колір']) !!}
                        {!! Lte3::colorpicker('vars_array[appearance][colors][hover]', \Variable::getArray('appearance.colors.hover', ''), ['label' => 'Колір при наведенні']) !!}
                        {!! Lte3::colorpicker('vars_array[appearance][colors][shadow]', \Variable::getArray('appearance.colors.shadow', ''), ['label' => 'Колір тіні']) !!}
                        {!! Lte3::colorpicker('vars_array[appearance][colors][button_text]', \Variable::getArray('appearance.colors.button_text', ''), ['label' => 'Колір тексту в кнопках']) !!}
                        --}}
                        {!! Lte3::lfmImage('vars_array[appearance][photos][top]', \Variable::getArray('appearance.photos.top', ''), ['label' => 'Довге фото над header-ом']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Логотипи</h3>
                    </div>
                    <div class="card-body">
                        {!! Lte3::lfmImage('vars_array[appearance][logo][white]', \Variable::getArray('appearance.logo.white', ''), ['label' => 'Логотип (Світла тема)']) !!}
                        {!! Lte3::lfmImage('vars_array[appearance][logo][black]', \Variable::getArray('appearance.logo.black', ''), ['label' => 'Логотип (Темна тема)']) !!}

                        {!! Lte3::lfmImage('vars_array[appearance][logo][white_mobile]', \Variable::getArray('appearance.logo.white_mobile', ''), ['label' => 'Логотип Мобільний (Світла тема)']) !!}
                        {!! Lte3::lfmImage('vars_array[appearance][logo][black_mobile]', \Variable::getArray('appearance.logo.black_mobile', ''), ['label' => 'Логотип Мобільний(Темна тема)']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Favicons</h3>
                    </div>
                    <div class="card-body">

                        {!! Lte3::lfmFile('vars_array[appearance][favicons_manifest]', \Variable::getArray('appearance.favicons_manifest', ''), ['label' => 'Файл Webmanifest']) !!}
                        <div><small>* має бути application/manifest+json або application/json</small></div>

                        {{-- MULTYPLE: --}}
                        <div class="card m-3 f-wrap f-multyblocks" data-fn-inits="initLfmBtn,initEditors,initColorpicker">
                            <div class="card-body">
                                <div class="f-items">
                                    {{-- TEMPLATE FIELDS --}}
                                    <template class="f-item-template">
                                        <div class="f-item">
                                            <div class="form-group">
                                                <label>Rel</label>
                                                <select class="form-control" name='appearance[favicons][$i][rel]'>
                                                    <option value="icon">icon</option>
                                                    <option value="apple-touch-icon">apple-touch-icon</option>
                                                    <option value="mask-icon">mask-icon</option>
                                                </select>
                                            </div>
                                            {!! Lte3::colorpicker('vars_array[appearance][favicons][$i][color]', null, ['label' => 'Color', 'default' => '#FFFFFF']) !!}
                                            {!! Lte3::lfmFile('vars_array[appearance][favicons][$i][href]', null, ['label' => 'Favicon File',]) !!}
                                            {!! Lte3::text('vars_array[appearance][favicons][$i][sizes]', null, ['label' => 'Sizes', 'disabled' => 1]) !!}
                                        </div>
                                        <hr style="border: solid 1px black">
                                    </template>

                                    {{-- ALREADY SAVED MULTIPLE FIELDS --}}
                                    @if (($favicons = \Variable::getArray('appearance.favicons')))
                                        @foreach ($favicons as $icon)
                                            <div class="f-item">
                                                <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                                            class="fa fa-trash"></i></a>
                                                <div class="form-group">
                                                    <label>Rel</label>
                                                    <select class="form-control" name="vars_array[appearance][favicons][{{$loop->index}}][rel]">
                                                        <option value="icon" @if(Arr::get($icon, 'rel') === 'icon') selected @endif>icon</option>
                                                        <option value="apple-touch-icon" @if(Arr::get($icon, 'rel') === 'apple-touch-icon') selected @endif>apple-touch-icon</option>
                                                        <option value="mask-icon" @if(Arr::get($icon, 'rel') === 'mask-icon') selected @endif>mask-icon</option>
                                                    </select>
                                                </div>

                                                {!! Lte3::colorpicker("vars_array[appearance][favicons][{$loop->index}][color]", Arr::get($icon, 'color'), ['label' => 'Color', 'default' => '#FFFFFF']) !!}
                                                {!! Lte3::lfmFile("vars_array[appearance][favicons][{$loop->index}][href]", Arr::get($icon, 'href'), ['label' => 'Favicon File', 'is_file' => true, 'lfm_category' => 'file']) !!}
                                                {!! Lte3::text("vars_array[appearance][favicons][{$loop->index}][sizes]", Arr::get($icon, 'sizes'), ['label' => 'Sizes', 'disabled' => 1]) !!}
                                            </div>
                                            <hr style="border: solid 1px black">
                                        @endforeach
                                    @else
                                        <p class="js-msg-empty">Favicons не завантежені 😢</p>
                                    @endisset
                                </div>
                                <a href="" class="btn btn-info btn-xs float-right js-btn-add"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Шрифти</h3>
                    </div>
                    <div class="card-body">

                        {{-- TODO Fonts https://i.imgur.com/KJ0dluL.png --}}


                    </div>
                </div>
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
