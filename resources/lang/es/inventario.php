<?php

return [
    'config_menu_auditores'         => 'Auditores',
    'config_menu_familias'          => 'Familias',
    'config_menu_materiales'        => 'Materiales',
    'config_menu_tipos_inventarios' => 'Tipos de inventario',
    'config_menu_inventarios'       => 'Inventarios',
    'config_menu_tipo_ubicacion'    => 'Tipo Ubicaci&oacute;n',
    'config_menu_ubicaciones'       => 'Ubicaciones',
    'config_menu_centros'           => 'Centros',
    'config_menu_almacenes'         => 'Almacenes',
    'config_menu_unidades_medida'   => 'Unidades de medida',

    'menu_reporte_hoja'      => 'Hoja',
    'menu_reporte_mat'       => 'Material',
    'menu_reporte_faltante'  => 'Faltante-Sobrante',
    'menu_reporte_ubicacion' => 'Ubicaci&oacute;n',
    'menu_reporte_tip_ubic'  => 'Tipos Ubicaci&oacute;n',
    'menu_reporte_ajustes'   => 'Ajustes',

    'menu_ajustes'     => 'Ajustes de inventario',
    'menu_upload'      => 'Subir stock',
    'menu_print'       => 'Imprimir hojas',
    'menu_act_precios' => 'Actualiza precios cat&aacute,logo',

    'inventario'      => 'Inventario',
    'page'            => 'Hoja',
    'auditor'         => 'Auditor',
    'button_new_line' => 'Nuevo material...',

    'form_new'               => 'Ingreso datos inventario',
    'form_new_material'      => 'Material',
    'form_new_material_placeholder' => 'Buscar...',
    'form_new_button_delete' => 'Borrar',
    'form_new_button_add'    => 'Agregar registro inventario',
    'form_new_button_edit'   => 'Modificar registro inventario',
    'form_new_button_cancel' => 'Cancelar',
    'digit_button_save_page' => 'Guardar hoja',

    'digit_th_ubicacion'          => 'ubicaci&oacute;n',
    'digit_th_material'           => 'material',
    'digit_th_descripcion'        => 'descripci&oacute;n',
    'digit_th_lote'               => 'lote',
    'digit_th_centro'             => 'centro',
    'digit_th_almacen'            => 'almac&eacute;n',
    'digit_th_UM'                 => 'UM',
    'digit_th_cant_sap'           => 'cant SAP',
    'digit_th_cant_fisica'        => 'cant f&iacute;sica',
    'digit_th_HU'                 => 'HU',
    'digit_th_observacion'        => 'observaci&oacute;n',
    'digit_th_hoja'               => 'hoja',
    'digit_th_cant_ajuste'        => 'cant ajuste',
    'digit_th_dif'                => 'dif',
    'digit_th_tipo_dif'           => 'tipo dif',
    'digit_th_observacion_ajuste' => 'observaci&oacute;n ajuste',

    'digit_msg_save'   => ':cantidadLineas linea(s) modificadas correctamente en hoja :hoja.',
    'digit_msg_delete' => 'Linea (id=%d) borrada correctamente en hoja %d.',
    'digit_msg_add'    => 'Linea agregada correctamente en hoja %d.',
    'adjust_msg_save'  => '%d linea(s) modificadas correctamente.',
    'adjust_link_hide' => 'Ocultar lineas regularizadas',
    'adjust_link_show' => 'Mostrar lineas regularizadas',

    'report_filter'                 => 'Buscar texto...',
    'report_check_ocultar_regs'     => 'Ocultar registros sin diferencias',
    'report_check_incluir_ajustes'  => 'Incluir ajustes de inventario',
    'report_check_incluir_familias' => 'Incluir familias de productos',
    'report_label_inventario'       => 'Inventario',
    'report_label_faltante'         => 'Faltante',
    'report_label_sobrante'         => 'Sobrante',
    'report_label_OK'               => 'OK',
    'report_save'                   => 'Guardar ajustes',

    'upload_label_fieldset'   => 'Subir archivo de stock',
    'upload_label_inventario' => 'Inventario',
    'upload_label_file'       => 'Archivo',
    'upload_label_password'   => 'Clave administrador',
    'upload_label_format'     => 'Formato del archivo',
    'upload_label_progress'   => 'Progreso',
    'upload_error'            => 'Se produjo un error tratar de subir el archivo de inventario.',
    'upload_warning_line1'    => 'ADVERTENCIA',
    'upload_warning_line2'    => 'Al subir un archivo se eliminar&aacute,n <strong>TODOS</strong> los registros asociados al inventario',
    'upload_status_OK'        => 'Registros cargados OK:',
    'upload_status_error'     => 'Registros con error:',
    'upload_button_load'      => 'Ejecutar carga',
    'upload_button_upload'    => 'Subir archivo',
    'upload_format_file'      => "Archivo de texto
Extension .txt
Campos separados por tabulación

Campos
        Ubicacion
        [HU - eliminada]
        Catalogo
        Descripcion catalogo
        Lote
        Centro
        Almacen
        Unidad de medida
        Stock SAP
        Hoja",

    'print_label_legend'       => 'Imprimir inventario',
    'print_label_inventario'   => 'Inventario',
    'print_label_page_from'    => 'P&aacute,gina desde',
    'print_label_page_to'      => 'P&aacute,gina hasta',
    'print_label_options'      => 'Opciones',
    'print_check_hide_columns' => 'Oculta columnas [STOCK_SAP] y [F/A]',
    'print_button_print'       => 'Imprimir',

    'act_precios_button' => 'Actualizar precios',
    'act_precios_msg'    => 'Actualizados %s registros.',

];
