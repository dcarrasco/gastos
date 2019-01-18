<?php

namespace App\OrmModel;

use Illuminate\Http\Request;

trait UsesCards
{
    public function renderCards(Request $request)
    {
        return collect($this->cards($request))->map(function($cardClass) use ($request) {
            return $cardClass->render($request);
        });
    }
}
