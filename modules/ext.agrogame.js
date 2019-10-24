(function ($, mw){

    loadPageInfo(mw.config.get( 'wgPageName' ));

    function loadPageInfo( articleName) {
        ( new mw.Api() ).post( {
            action: 'avgpage',
            format: 'json',
            pagetitle: articleName
        } )
            .done( function ( data ) {
                console.log(data);
                    if(parseFloat(data.avg) >= 4){
                        loadValidatedSign();
                    }else{
                        loadWarningSign();
                    };
                }
            )
    }
    loadWarningSign
    function loadValidatedSign(){
        $("#mw-content-text").prepend('<div id="info-sign-container" class="successbox"></div>');
        $("#info-sign-container").append('<div>Articulo validado<strong id="tick-sign" class="sign">✔</strong></div>');
    }
    
    function loadWarningSign(){
        $("#mw-content-text").prepend('<div id="info-sign-container" class="warningbox"></div>');
        $("#info-sign-container").append('<strong id="warning-sign" class="sign">⚠</strong>');
        $("#info-sign-container").append('<div><strong>Advertencia!</strong> Este artículo aun no posee suficientes calificaciones para validar la información que contiene.</div>');
    }


}( jQuery, mw ) );
