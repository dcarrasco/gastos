<?php

namespace App\Http\Controllers\AdminBd;

use App\Http\Controllers\Controller;

class AdminBdController extends Controller
{

    public function showQueries()
    {
        $reload_secs = 15;

        $datos = array(
            'queries_data' => $this->adminbd_model->get_running_queries(),
            'extra_styles' => $reload_secs > 0 ? "<meta http-equiv=\"refresh\" content=\"{$reload_secs};URL='".$this->router->class."'\">" : '',
            'actualizado_el' => date('Y-m-d H:i:s'),
        );

        return view('adminbd.queries');
    }
}
