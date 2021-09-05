<?php

namespace App\Http\Controllers\Orm;

use Illuminate\Http\Request;

trait OrmCard
{
    /**
     * Recupera el recurso para ser usado en llamadas ajax
     *
     * @param  Request  $request
     * @return array<string>
     */
    public function ajaxCard(Request $request): array
    {
        return collect($this->cards($request))
            ->first->hasUriKey($request->input('uri-key'))
            ->contentAjaxRequest($request);
    }
}
