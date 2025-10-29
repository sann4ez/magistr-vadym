{!! Lte3::hidden('type', 'contacts') !!}

{!! Lte3::textarea('content[desc]', null, ['label' => 'Опис']) !!}

{!! Lte3::email('content[email]', null, ['label' => 'Електронна адреса']) !!}

<div class="row">
    <div class="col-md-4">
        {!! Lte3::textarea('content[copyright]', null, ['label' => 'Підвал (копірайт)']) !!}
    </div>
    <div class="col-md-4">
        {!! Lte3::textarea('content[develop_text]', null, ['label' => 'Підвал (текст ким розроблено)']) !!}
    </div>
    <div class="col-md-4">
        {!! Lte3::url('content[develop_url]', null, ['label' => 'Підвал (посилання ким розроблено)']) !!}
    </div>
</div>

{!! Lte3::text('content[qr]', null, ['label' => 'QR code - посилання']) !!}

<div class="row">
    <div class="col-md-6">
        {!! Lte3::url('content[google_play_url]', null, ['label' => 'Google Play посилання']) !!}
    </div>
    <div class="col-md-6">
        {!! Lte3::lfmImage('content[google_play_img]', isset($block) ? $block->getContent('google_play_img') : null, ['label' => 'Google Play картинка']) !!}
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        {!! Lte3::url('content[app_store_url]', null, ['label' => 'App Store посилання']) !!}
    </div>
    <div class="col-md-6">
        {!! Lte3::lfmImage('content[app_store_img]', isset($block) ? $block->getContent('app_store_img') : null, ['label' => 'App Store картинка']) !!}
    </div>
</div>