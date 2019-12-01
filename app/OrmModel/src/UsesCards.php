<?php

namespace App\OrmModel\src;

use Illuminate\Http\Request;

trait UsesCards
{
    public function renderCards(Request $request)
    {
        return collect($this->cards($request))->map->render($request);
    }
}
