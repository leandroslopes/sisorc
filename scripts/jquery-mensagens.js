var showMsgCadastrado = function(arquivo) {
    $(".msgCadastrado").dialog({
        dialogClass: "no-close",
        show: "slow",
        buttons: [{
                text: "OK",
                click: function() {
                    $(this).dialog("close");
                    $(location).attr("href", arquivo);
                }
            }]
    });
};

var showMsgSucesso = function(arquivo) {
    $(".msgSucesso").dialog({
        dialogClass: "no-close",
        show: "slow",
        buttons: [{
                text: "OK",
                click: function() {
                    $(this).dialog("close");
                    $(location).attr("href", arquivo);
                }
            }]
    });
};

var showMsgErro = function(arquivo) {
    $(".msgErro").dialog({
        dialogClass: "no-close",
        show: "slow",
        buttons: [{
                text: "OK",
                click: function() {
                    $(this).dialog("close");
                    $(location).attr("href", arquivo);
                }
            }]
    });
};