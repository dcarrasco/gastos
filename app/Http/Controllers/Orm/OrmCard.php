<?php

namespace App\Http\Controllers\Orm;

use Illuminate\Http\Request;

trait OrmCard
{
    /**
     * Recupera el recurso para ser usado en llamadas ajax
     *
     * @param  Request  $request
     * @param  string  $resourceClass Nombre del recurso
     * @return
     */
    public function ajaxCard(Request $request)
    {
        return collect($this->cards($request))
            ->first(function ($card) use ($request) {
                return $card->urikey() === $request->input('uri-key');
            })
            ->calculate($request);
    }
}
