var ajaxInteractionsListener = function(){

    $("select , input").change( function(){
        prepareRequest('form');
    });


    $("body").on('click' , '.pagination li a' , function(event ){
        event.preventDefault();
        prepareRequest('pagination' , $(this));
    } );

}


var readInputData = function(){

    var checked = $('input#nombre:checked').length > 0;
    var condition = $('select#tipo-relacion').val();
    var page = $('.pagination li.active a').attr('data-page');
    return { 'nombre' : checked , 'relacion' : condition , 'page' : page };

}

var readPaginationData = function(context){
    data = readInputData();
    data.page = context.attr('data-page');

    return data;
}


var prepareRequest = function( type ,  context ){
    if(type == 'form'){
        return sendRequest( '/empresa/ajax-listado-relaciones' , readInputData()  );
    }

    if( type == 'pagination' )
        return sendRequest('/empresa/ajax-listado-relaciones' , readPaginationData(context) );
}



var sendRequest = function( url , data  ){

    console.log(data);

    $.ajax({
        url : url,
        data : data,
        method : 'POST',
        beforeSend : waitAnimationBeforeSend($("#listado-relaciones-empresas")),

        success : function(response){
            renderNewList( $("#listado-relaciones-empresas") , response );
            console.log(response);
        },

        failure : function( xhr ){
            Materialize.toast('Ooops! Parece que ha habido un error informático. Por favor, vuelve a intentarlo y si el error persiste contacta con el soporte ' , 4000);
        }
    });
}

var waitAnimationBeforeSend = function( $context ){
    $context.html('<div class="progress"> <div class="indeterminate"></div> </div>');
}

var renderNewList = function( context , response ){
    context.html(response.html);
}





$(function(){
    $('select').material_select();
    ajaxInteractionsListener();
});
