(function( $ ){
    $.fn.toggler = function( fn, fn2 ) {
        var args = arguments,guid = fn.guid || $.guid++,i=0,
        toggler = function( event ) {
            var lastToggle = ( $._data( this, "lastToggle" + fn.guid ) || 0 ) % i;
            $._data( this, "lastToggle" + fn.guid, lastToggle + 1 );
            event.preventDefault();
            return args[ lastToggle ].apply( this, arguments ) || false;
        };
        toggler.guid = guid;
        while ( i < args.length ) {
            args[ i++ ].guid = guid;
        }
        return this.click( toggler );
    };
})( jQuery );

function showLoading(object)
{
    $(object).children().wrapAll('<div id="wrap" class="hide" />');
    $(object).append('<div class="center-center"><div class="center-vertically"><i class="fa fa-spinner fa-spin fa-4x"></i></div></div>');
}

function hideLoading(object)
{
    $(object).find('#wrap').children().unwrap();
    $(object).find('.center-center').remove();
}

$(document).ready(function(){

    $('[title]').not('[data-toggle="popover"]').attr('rel','tooltip');
    $("[rel=tooltip]").tooltip({ placement: 'top'});

    $('[data-toggle="popover"]').click(function(){
        $('[data-toggle="popover"]').popover('hide');
        //$(this).popover();
    }).popover();

    

    $('[href=#]').click(function(event){

        event.preventDefault();
    })

    $('#accordion .panel, #accordion_statement .panel').mouseenter(function(){
        $(this).removeClass('panel-default').addClass('panel-primary');
    }).mouseleave(function(){
        if (! $(this).is('.active'))
            $(this).removeClass('panel-primary').addClass('panel-default');
    });

    $('#accordion .panel.no-collapse').click(function(){
        href = $(this).find('a.underline').attr('href');
        $(location).attr('href',href);
    });

    $('#accordion .collapse').on('show.bs.collapse', function(){
        $('#accordion .panel-primary').removeClass('panel-primary active').addClass('panel-default');
        $('[href="#' + this.id + '"]').parents('.panel-default').addClass('active panel-primary').removeClass('panel-default');
    }).on('hide.bs.collapse', function(){
        $('[href="#' + this.id + '"]').parents('.panel-default').removeClass('panel-primary active').addClass('panel-default');
    });

    $('#accordion .panel.active').addClass('panel-primary').removeClass('panel-default');

    $.extend( true, $.fn.dataTable.defaults, {
        "sDom": "<'row'<'col-sm-12'<'pull-right'f><'pull-left'l>r<'clearfix'>>>t<'row'<'col-sm-12'<'pull-left'i><'pull-right'p><'clearfix'>>>",
        "sPaginationType": "bs_full",
        "iDisplayLength": 5,
        "oLanguage": {
            "sLengthMenu":     "_MENU_",
            "sSearch":         "",
            "oPaginate": {
                "sFirst":    "",
                "sLast":     "",
                "sNext":     "",
                "sPrevious": ""
            }
        }
    } );

    var current_url = location.origin + location.pathname;

    $('#accordion .panel').each(function(){

        if ($(this).find('a[href="' + current_url + '"]').length > 0){
            $('#accordion .panel.active').removeClass('active');
            $(this).removeClass('panel-default').addClass('panel-primary').addClass('active');

            if (! $(this).is('.no-collapse')){

                //collapse = $(this).find('[data-toggle="collapse"]').attr('href');
                //$(collapse).collapse('show');

            }
            return false;
        }

    })


    $('.datatable').each(function(){

        if ($(this).find('tbody tr').length > 5){

            $(this).dataTable({
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
                "oLanguage": {
                    "sLengthMenu": "Mostrar &nbsp;_MENU_"
                }
            }); 

            //$(this).data('datatable', $(this).dataTable());

        }else{
            $(this).parents('.panel-table').removeClass('panel-primary').addClass('panel-default');
        }


    });

    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Buscar');
    $('.dataTables_length select').addClass('form-control');


    bootbox.setDefaults({
        title: "Mensaje de la Oficina Virtual",
    });


    setTimeout(function(){
        $('.alert.flash').slideUp('slow');
    }, 2000);

    var $objects = $('.panel.panel-primary').find('.panel-tabs').find('li').slice(1);

    if ($objects.filter('.active').length === 0)
    {
        $objects.closest('.panel.panel-primary').find('.tab-content').find('.tab-pane').removeClass('active');
        var href = $objects.first().addClass('active').children('a').attr('href');
        $(href).addClass('active');
    }

    //console.log($objects);
});