<?php

namespace App\Models\Traits;

use Illuminate\Support\Arr;
use Spatie\Image\Enums\Fit;

trait InteractsWithMedia
{
    use \Fomvasss\MediaLibraryExtension\HasMedia\InteractsWithMedia;

    public function customMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        // TODO Domain // $media->model

        $options = [];

        // TODO: Optimize N+1!!!
        if ($media->model?->domain_id && $media->model->domain) {
            \Domain::setSelected($media->model->domain);
        }

        switch ($media->model?->getMorphClass()) {
            case 'term':
                //$options = \Domain::getOpt("terms.vocabularies.{$media->model->vocabulary}.conversions", []);
                $this->addOgMediaConversion('image');
            break;
            case 'post':
//                $options = \Domain::getOpt('posts.conversions', []);
                $this->addOgMediaConversion('image');
            break;
            case 'item':
                $options = \Domain::getOpt('items.conversions', []);
                $this->addOgMediaConversion('image');
            break;
        }

        // https://spatie.be/docs/image/v1/image-manipulations/overview
        // https://spatie.be/docs/image/v1/image-manipulations/resizing-images#crop
        foreach ($options as $option) {
            $conversion = $this->addMediaConversion($option['name']);
            if ($val = Arr::get($option, 'fit')) {
                list($method, $width, $height) = $val;
                $conversion->fit($method, $width, $height);
            }

            if ($val = Arr::get($option, 'format')) {
                $conversion ->format($val);
            }

            if ($val = Arr::get($option, 'quality')) {
                $conversion ->quality($val);
            }

            if ($val = Arr::get($option, 'performOn')) {
                $conversion->performOnCollections(...Arr::wrap($val));
            }
        }

    }

    protected function addOgMediaConversion($collectionName)
    {
        $this->addMediaConversion('og_image')
            ->fit(Fit::FillMax, 1200, 630)
            ->format('png')->background('#FFFFFF')
            ->performOnCollections($collectionName);
    }
}
