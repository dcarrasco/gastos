<?php

namespace App\OrmModel;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class Card
{
    public $width = '1/3';

    public function component()
    {
        return '';
    }

    public function render(Request $request)
    {
        return view('orm.card', [
            'content' => $this->content($request),
            'contentScript' => $this->contentScript($request),
            'cardWidth' => $this->bootstrapCardWidth(),
            'title' => $this->title(),
            'cardId' => $this->cardId(),
            'ranges' => $this->ranges(),
            'uriKey' => $this->uriKey(),
            'resource' => $request->segment(2),
        ])->render();
    }

    protected function bootstrapCardWidth()
    {
        return Arr::get([
            '1/2' => 'col-md-6',
            '1/3' => 'col-md-4',
            '2/3' => 'col-md-8',
            'full' => 'col-md-12',
        ], $this->width, '');
    }

    public function title()
    {
        return Str::title(str_replace('_', ' ', Str::snake(class_basename($this))));
    }

    public function width($width = '')
    {
        $this->width = $width;

        return $this;
    }

    protected function cardId()
    {
        return spl_object_hash($this);
    }

}
