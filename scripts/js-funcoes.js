function atualizaPagina() {
    document.location.reload();
}

function updateTips(t, tips) {
    tips
            .text(t)
            .addClass("ui-state-highlight");
    setTimeout(function() {
        tips.removeClass("ui-state-highlight", 1500);
    }, 500);
}

function checkLength(o, n, min, max, tips) {
    if (o.val().length > max || o.val().length < min) {
        o.addClass("ui-state-error");
        updateTips("O número de caracteres do campo " + n + " deve ser entre " + min + " and " + max + ".", tips);
        return false;
    } else {
        return true;
    }
}

function checkRegexp(o, regexp, n, tips) {
    if (!(regexp.test(o.val()))) {
        o.addClass("ui-state-error");
        updateTips(n, tips);
        return false;
    } else {
        return true;
    }
}

function updateMessage(mensagem) {
    mensagem.removeClass("oculto");
    mensagem.addClass("visivel");
    setTimeout(function() {
        mensagem.removeClass("visivel");
        mensagem.addClass("oculto");
    }, 3000);
    setTimeout(function() {
        atualizaPagina();
    }, 1000);

}