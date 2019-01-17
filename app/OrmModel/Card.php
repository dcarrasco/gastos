<?php

namespace App\OrmModel;

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
        $data = $this->data($request);
        $cardWidth = $this->bootstrapCardWidth();
        $title = $this->title();
        $cardId = spl_object_hash($this);

        return view('orm.card', compact('data', 'cardWidth', 'title', 'cardId'))->render();
    }

    protected function bootstrapCardWidth()
    {
        return array_get([
            '1/2' => 'col-md-6',
            '1/3' => 'col-md-4',
        ], $this->width, '');
    }

    public function title()
    {
        return title_case(str_replace('_', ' ', snake_case(class_basename($this))));
    }



    // Implementation of the class
}
