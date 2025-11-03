@php
    $varGroup = \Domain::getGroup();
    \Variable::setGroup($varGroup);
@endphp
    <div class="card card-solid">
        <div class="card-header with-border">
            <h3 class="card-title">SEO <i class=""></i></h3>
        </div>

        <div class="card-body">

            {!! Lte3::formOpen(['action' => route('admin.settings.save',), null, 'method' => 'POST']) !!}
            <input type="hidden" name="group" value="{{ $varGroup }}">
    {{--
            {!! Lte3::checkbox('vars_array[seo][close]', \Variable::getArray('seo.close', 0) , [
               'label' => 'Закрити сайт від індексації',
               'class_control' => 'custom-switch'
           ]) !!}
    --}}

            {{--
            {!! Lte3::checkbox('vars_array[seo][robots][index]', \Variable::getArray('seo.robots.index', false) , [
               'label' => 'index',
               'checked_value' => true,
                'unchecked_value' => false,
               'class_control' => 'custom-switch'
           ]) !!}
            {!! Lte3::checkbox('vars_array[seo][robots][follow]', \Variable::getArray('seo.robots.follow', false) , [
               'label' => 'follow',
               'checked_value' => true,
                'unchecked_value' => false,
               'class_control' => 'custom-switch'
           ]) !!}
            --}}


            @php
                $patterns = [
                    ['key' => 'page', 'title' => 'Сторінка', 'tokens' => \App\Models\Page::tokensList()],
                    ['key' => 'post', 'title' => 'Стаття', 'tokens' => \App\Models\Post::tokensList()],
                    ['key' => 'post_categories', 'title' => 'Категорія статті', 'tokens' => \App\Models\Term::tokensList()],
                    ['key' => 'variation', 'title' => 'Варіація', 'tokens' => array_merge(\App\Models\Shop\ProductVariation::tokensList(), \App\Models\Shop\Product::tokensList())],
                    ['key' => 'product_categories', 'title' => 'Категорія товару', 'tokens' => \App\Models\Term::tokensList()],
                    ['key' => 'promotion', 'title' => 'Акція', 'tokens' => \App\Models\Shop\Promotion::tokensList()],
                    ['key' => 'brands', 'title' => 'Бренди', 'tokens' => \App\Models\Term::tokensList()],
                    ['key' => 'tags', 'title' => 'Теги', 'tokens' => \App\Models\Term::tokensList()],
                ]
            @endphp

            @foreach($patterns as $pattern)
                <div class="card {{--card-outline--}} card-success  {{--collapsed-card--}}">
                    <div class="card-header" data-card-widget="collapse">
                        <h3 class="card-title" >{{ $pattern['title'] }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                {!! Lte3::textarea("vars_array[seo][patterns][{$pattern['key']}][title]", \Variable::getArray("seo.patterns.{$pattern['key']}.title", ''), ['label' => 'tag (title)', 'rows' => 2]) !!}
                                {!! Lte3::textarea("vars_array[seo][patterns][{$pattern['key']}][description]", \Variable::getArray("seo.patterns.{$pattern['key']}.description", ''), ['label' => 'meta (description)', 'rows' => 2]) !!}
                            </div>
                            <div class="col-md-4">
                                @include('admin.parts.str-tokens-callout', ['tokens' => \Illuminate\Support\Arr::get($pattern, 'tokens'), 'height' => 1])
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="card {{--card-outline--}} card-success  {{--collapsed-card--}}">
                <div class="card-header" data-card-widget="collapse">
                    <h3 class="card-title" >Файл <strong>robots.txt</strong></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                        @php
                            $path = public_path('robots.txt');
                            $robots = '';
                            if (\File::exists($path)) {
                                $robots = \File::get($path);
                            }
                        @endphp
                        {!! Lte3::textarea("vars_file_content[robots][content]", $robots, [
                            'label' => 'Контент',
                            'rows' => 5
                        ]) !!}
                        {!! Lte3::hidden('vars_file_content[robots][path]', $path) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row" hidden>
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Facebook</h3>
                    </div>
                    <div class="card-body">
                        {!! Lte3::text('vars_array[seo][fb][app_id]', \Variable::getArray("seo.fb.app_id", null), ['label' => 'ID Додатку']) !!}
                        <p class="small">* <code>&lt;meta property="fb:app_id" content="YOUR_APP_ID" /&gt;</code>
                        </p>
                        {!! Lte3::text('vars_array[seo][fb][pixel_id]', \Variable::getArray("seo.fb.pixel_id", null), ['label' => 'Pixel ID']) !!}
                        {!! Lte3::text('vars_array[seo][fb][domain_verification]', \Variable::getArray("seo.fb.domain_verification", null), ['label' => 'Domain Verification']) !!}
                        <p class="small">* <code>&lt;meta name="facebook-domain-verification"
                                content="YOUR_VERIFICATION"/&gt;</code></p>
                        {!! Lte3::text('vars_array[seo][fb][access_token]', \Variable::getArray("seo.fb.access_token", null), ['label' => 'Access Токен']) !!}
                        <p class="small">* Use in backend FB Events</p>
                        {!! Lte3::checkbox('vars_array[seo][fb][event_purchase]', \Variable::getArray("seo.fb.event_purchase", null), [
                            'label' => 'Event: Purchase',
                            'class_control' => 'custom-switch',
                        ]) !!}
                        {!! Lte3::checkbox('vars_array[seo][fb][event_subscribe]', \Variable::getArray("seo.fb.event_subscribe", null), [
                            'label' => 'Event: Subscribe',
                            'class_control' => 'custom-switch',
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6" hidden>
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Google</h3>
                    </div>
                    <div class="card-body">
                        {!! Lte3::text('vars_array[seo][google][ga_tracking_id]', \Variable::getArray("seo.google.tracking_id", null), ['label' => 'GA_TRACKING_ID']) !!}
                        <p class="small">* <code>&lt;meta property="fb:app_id" content="YOUR_APP_ID" /&gt;</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer text-right">
        {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
    </div>
</div>
{!! Lte3::formClose() !!}

{{--
<div class="callout callout-info block-perform-confirmed" style="">
    <p> карта сайту</p>
</div>
--}}
