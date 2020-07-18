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
    public function ajaxCard(Request $request): string
    {
        return collect($this->cards($request))
            ->first->hasUriKey($request->input('uri-key'))
            ->content($request)->toHtml();
    }
}
