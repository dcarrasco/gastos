/* global $ */

$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    // ENCABEZADOS
    $('table.reporte th span').css('cursor', 'pointer');

    $('table.reporte th span').click(function (event) {
        event.preventDefault();
        $('form input[name="sort"]').val($(this).attr('data-sort'));
        $('form').submit();
    });


    // LINEAS SUBTOTALES
    var count_linea_reporte = 0;
    $('table.reporte > tbody > tr').each(function () {
        if ($(this).find('td > span.glyphicon').length) {
            count_linea_reporte++;
            $(this).attr('data-tipo','subtot-header');
            $(this).attr('data-num',count_linea_reporte);
            $(this).css('cursor','pointer');
            $(this).addClass('info');
        } else {
            $(this).attr('data-tipo','data-detail');
            $(this).attr('data-num',count_linea_reporte);
        }
    });

    $('table.reporte > tbody > tr[data-tipo="subtot-header"]').click(function (event) {
        event.preventDefault();
        var detalle = $('table.reporte > tbody > tr[data-tipo="data-detail"][data-num="'+$(this).attr('data-num')+'"]');
        detalle.toggle();

        if (detalle.find(':visible').length) {
            $(this).find('td:first-child > span').removeClass('glyphicon-plus-sign').addClass('glyphicon-minus-sign');
        }
        else {
            $(this).find('td:first-child > span').removeClass('glyphicon-minus-sign').addClass('glyphicon-plus-sign');
        }
    });

});

/* ============================================================================================ */
/* Fixed Header                                                                                 */
/* ============================================================================================ */

(function($) {

$.fn.fixedHeader = function (options) {
    var config = {
        topOffset: 40,
        bgColor: '#FFF'
    };

    if (options){ $.extend(config, options); }

    return this.each( function() {
        var o = $(this);

        var $win = $(window)
        , $head = $('thead.header', o)
        , isFixed = 0;
        var headTop = $head.length && $head.offset().top - config.topOffset;

        function processScroll() {
            if (!o.is(':visible')) return;
            var i, scrollTop = $win.scrollTop();
            var t = $head.length && $head.offset().top - config.topOffset;
            if (!isFixed && headTop != t) { headTop = t; }
            if      (scrollTop >= headTop && !isFixed) { isFixed = 1; }
            else if (scrollTop <= headTop && isFixed) { isFixed = 0; }
            isFixed ? $('thead.header-copy', o).removeClass('hide')
                    : $('thead.header-copy', o).addClass('hide');
        }

        $win.on('scroll', processScroll);

        // hack sad times - holdover until rewrite for 2.1
        $head.on('click', function () {
            if (!isFixed) setTimeout(function () {  $win.scrollTop($win.scrollTop() - 47) }, 10);
        })

        $head.clone().removeClass('header').addClass('header-copy header-fixed').appendTo(o);

        var ww = [];
        o.find('thead.header > tr:first > th').each(function (i, h){
            ww.push($(h).width()+10);
        });
        $.each(ww, function (i, w){
            o.find('thead.header > tr > th:eq('+i+'), thead.header-copy > tr > th:eq('+i+')').css({width: w});
        });

        o.find('thead.header-copy').css({ margin:'0 auto',
                                        width: o.width(),
                                       'background-color':config.bgColor });
        processScroll();
    });
};

})(jQuery);

$(document).ready(function() {
    $('table.table-fixed-header').fixedHeader({topOffset: 0});
});