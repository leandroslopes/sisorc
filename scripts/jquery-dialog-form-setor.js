$(function() {
    $.noConflict();

    /* SETOR */
    $("#cadastrarSetor").click(function() {
        var setor = $("#setor"), allFields = $([]).add(setor), tips = $(".validateTips");
        $(".dialogForm").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 210,
            width: 350,
            modal: true,
            resizable: false,
            title: "CADASTRAR SETOR",
            buttons: {
                "Cadastrar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(setor, "setor", 3, 20, tips);
                    bValid = bValid && checkRegexp(setor, /^[a-z]([0-9a-z_])+$/i, "Campo setor deve conter a-z, 0-9, underlines, letras sem acento, começando com uma letra.", tips);

                    if (bValid) {
                        $.ajax({
                            url: "cadastrarSetor.php",
                            type: "post",
                            data: $("#frmCadastrarSetor").serialize(),
                            success: function(retorno) {
                                var mensagem, cadastrou = retorno.trim();
                                if (cadastrou === "CADASTRADO") {
                                    mensagem = $("#msgCadastradoDialog");
                                    updateMessage(mensagem);
                                } else if (cadastrou === "TRUE") {
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
                    tips.text("Informe o nome do setor.");
                    $(this).dialog("destroy");
                }
            }
        });
        $(".dialogForm").dialog("open");
    });

    $(document).on('click', '.excluirSetor', function() {
        var setor = $(this).parent().parent()[0], nomeSetor = setor.cells[0].innerHTML, idSetor = setor.cells[2].getElementsByTagName("input")[0].value;
        $(".dialogConfirm").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 160,
            width: 320,
            modal: true,
            title: "EXCLUIR SETOR \"" + nomeSetor + "\"?",
            buttons: {
                "Excluir": function() {
                    $.ajax({
                        url: "excluirSetor.php",
                        type: "post",
                        data: "nomeSetor=" + nomeSetor + "&idSetor=" + idSetor,
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

    $(document).on('click', '.editarSetor', function() {
        var setor, idSetor, nomeSetor, campoSetor, allFields, tips;

        setor = $(this).parent().parent()[0];
        idSetor = setor.cells[2].getElementsByTagName("input")[0].value;
        nomeSetor = setor.cells[0].innerHTML;
        campoSetor = $("#setor");
        allFields = $([]).add(campoSetor);
        tips = $(".validateTips");
        campoSetor.val(nomeSetor);

        $(".dialogForm").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 210,
            width: 350,
            modal: true,
            title: "EDITAR SETOR",
            buttons: {
                "Editar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(campoSetor, "setor", 3, 20, tips);
                    bValid = bValid && checkRegexp(campoSetor, /^[a-z]([0-9a-z_])+$/i, "Campo setor deve conter a-z, 0-9, underlines, letras sem acento, começando com uma letra.", tips);

                    if (bValid) {
                        var data = "id=" + idSetor + "&" + $("#frmCadastrarSetor").serialize();
                        $.ajax({
                            url: "cadastrarSetor.php",
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
                        tips.text("Informe o nome do setor.");
                        $(this).dialog("destroy");
                    }
                },
                "Cancelar": function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome do setor.");
                    $(this).dialog("destroy");
                }
            }
        });
    });
});
