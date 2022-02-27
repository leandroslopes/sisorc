$(function() {
    $(".focus").focus();

    $(".soNumeros").keypress(function(event) {
        var tecla = (window.event) ? event.keyCode : event.which;
        if ((tecla > 47 && tecla < 58))
            return true;
        else {
            if (tecla !== 8)
                return false;
            else
                return true;
        }
    });

    $(".caixaAlta").keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });

    $(".retiraAcento").keyup(function() {
        var varString = new String($(this).val());
        var stringAcentos = new String("àâêôûãõáéíóúçüÀÂÊÔÛÃÕÁÉÍÓÚÇÜ");
        var stringSemAcento = new String("aaeouaoaeioucuAAEOUAOAEIOUCU");

        var i = new Number();
        var j = new Number();
        var cString = new String();
        var varRes = "";

        for (i = 0; i < varString.length; i++) {
            cString = varString.substring(i, i + 1);
            for (j = 0; j < stringAcentos.length; j++) {
                if (stringAcentos.substring(j, j + 1) === cString) {
                    cString = stringSemAcento.substring(j, j + 1);
                }
            }
            varRes += cString;
        }
        $(this).val(varRes);
    });

    $("#voltar").click(function() {
       history.back(); 
    });

    $.fn.redirecionar = function(pagina) {
        $(location).attr("href", pagina);
    };

    $.fn.novaAbaForm = function(form) {
        form.target = "_blank";
    };
});