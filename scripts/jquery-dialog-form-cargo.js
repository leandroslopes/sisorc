$(function() {
    $.noConflict();
    
    /* CARGO */
    $("#cadastrarCargo").click(function() {
        var cargo = $("#cargo"), allFields = $([]).add(cargo), tips = $(".validateTips");
        $(".dialogForm").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 210,
            width: 350,
            modal: true,
            resizable: false,
            title: "CADASTRAR CARGO",
            buttons: {
                "Cadastrar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(cargo, "cargo", 3, 20, tips);
                    bValid = bValid && checkRegexp(cargo, /^[a-z]([0-9a-z_])+$/i, "Campo cargo deve conter a-z, 0-9, underlines, letras sem acento, começando com uma letra.", tips);

                    if (bValid) {
                        $.ajax({
                            url: "cadastrarCargo.php",
                            type: "post",
                            data: $("#frmCadastrarCargo").serialize(),
                            success: function(retorno) {
                                var mensagem, cadastrou = retorno.trim();
                                if (cadastrou == "CADASTRADO") {
                                    mensagem = $("#msgCadastradoDialog");
                                    updateMessage(mensagem);
                                } else if (cadastrou == "TRUE") {
                                    mensagem = $("#msgSucessoDialog");
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $("#msgErroDialog");
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $("#msgErroDialog");
                                updateMessage(mensagem);
                            }
                        });
                        allFields.val("").removeClass("ui-state-error");
                        tips.text("Informe o nome do setor.");
                        $(this).dialog("destroy");
                    }
                },
                "Cancelar": function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome do cargo.");
                    $(this).dialog("destroy");
                }
            }
        });
        $(".dialogForm").dialog("open");
    });

    $(document).on('click', '.excluirCargo', function() {
        var cargo = $(this).parent().parent()[0], nomeCargo = cargo.cells[0].innerHTML, idCargo = cargo.cells[2].getElementsByTagName("input")[0].value;
        $(".dialogConfirm").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 160,
            width: 320,
            modal: true,
            title: "EXCLUIR CARGO \"" + nomeCargo + "\"?",
            buttons: {
                "Excluir": function() {
                    $.ajax({
                        url: "excluirCargo.php",
                        type: "post",
                        data: "nomeCargo=" + nomeCargo + "&idCargo=" + idCargo,
                        success: function(retorno) {
                            var mensagem, excluiu = retorno.trim();
                            if (excluiu === "CADASTRADO") {
                                mensagem = $("#msgProibicaoDialog");
                                updateMessage(mensagem);
                            } else if (excluiu === "TRUE") {
                                mensagem = $("#msgSucessoDialog");
                                updateMessage(mensagem);
                            } else {
                                mensagem = $("#msgErroDialog");
                                updateMessage(mensagem);
                            }
                        }
                    });
                    $(this).dialog("destroy");
                },
                "Cancelar": function() {
                    $(this).dialog("destroy");
                }
            }
        });
    });

    $(document).on('click', '.editarCargo', function() {
        var cargo, idCargo, nomeCargo, campoCargo, allFields, tips;

        cargo = $(this).parent().parent()[0];
        idCargo = cargo.cells[2].getElementsByTagName("input")[0].value;
        nomeCargo = cargo.cells[0].innerHTML;
        campoCargo = $("#cargo");
        allFields = $([]).add(campoCargo);
        tips = $(".validateTips");
        campoCargo.val(nomeCargo);

        $(".dialogForm").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 210,
            width: 350,
            modal: true,
            title: "EDITAR CARGO",
            buttons: {
                "Editar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(campoCargo, "cargo", 3, 20, tips);
                    bValid = bValid && checkRegexp(campoCargo, /^[a-z]([0-9a-z_])+$/i, "Campo cargo deve conter a-z, 0-9, underlines, letras sem acento, começando com uma letra.", tips);

                    if (bValid) {
                        var data = "id=" + idCargo + "&" + $("#frmCadastrarCargo").serialize();
                        $.ajax({
                            url: "cadastrarCargo.php",
                            type: "post",
                            data: data,
                            success: function(retorno) {
                                var mensagem, cadastrou = retorno.trim();
                                if (cadastrou == "CADASTRADO") {
                                    mensagem = $("#msgCadastradoDialog");
                                    updateMessage(mensagem);
                                } else if (cadastrou == "TRUE") {
                                    mensagem = $("#msgSucessoDialog");
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $("#msgErroDialog");
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $("#msgErroDialog");
                                updateMessage(mensagem);
                            }
                        });
                        allFields.val("").removeClass("ui-state-error");
                        tips.text("Informe o nome do cargo.");
                        $(this).dialog("destroy");
                    }
                },
                "Cancelar": function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome do cargo.");
                    $(this).dialog("destroy");
                }
            }
        });
    });
});