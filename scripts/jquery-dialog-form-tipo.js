$(function() {
    $.noConflict();

    /* TIPO */
    $("#cadastrarTipo").click(function() {
        var tipo = $("#tipo"), allFields = $([]).add(tipo), tips = $(".validateTips");
        $(".dialogFormTipo").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 220,
            width: 350,
            modal: true,
            resizable: false,
            title: "CADASTRAR TIPO",
            buttons: {
                "Cadastrar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(tipo, "tipo", 2, 5, tips);
                    bValid = bValid && checkRegexp(tipo, /^[a-z]([0-9a-z_])+$/i, "Campo tipo deve conter a-z, 0-9, underlines, letras sem acento, começando com uma letra.", tips);

                    if (bValid) {
                        $.ajax({
                            url: "cadastrarTipo.php",
                            type: "post",
                            data: $("#frmCadastrarTipo").serialize(),
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
                        tips.text("Informe o nome do tipo.");
                        $(this).dialog("destroy");
                    }
                },
                "Cancelar": function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome do tipo.");
                    $(this).dialog("destroy");
                }
            }
        });
        $(".dialogFormTipo").dialog("open");
    });

    $(document).on('click', '.editarTipo', function() {
        var tipo, idTipo, nomeTipo, campoTipo, allFields, tips;

        tipo = $(this).parent().parent()[0];
        idTipo = tipo.cells[2].getElementsByTagName("input")[0].value;
        nomeTipo = tipo.cells[1].innerHTML;
        campoTipo = $("#tipo");
        allFields = $([]).add(campoTipo);
        tips = $(".validateTips");
        campoTipo.val(nomeTipo);

        $(".dialogFormTipo").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 220,
            width: 350,
            modal: true,
            title: "EDITAR TIPO",
            buttons: {
                "Editar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(campoTipo, "tipo", 2, 5, tips);
                    bValid = bValid && checkRegexp(campoTipo, /^[a-z]([0-9a-z_])+$/i, "Campo tipo deve conter a-z, 0-9, underlines, letras sem acento, começando com uma letra.", tips);

                    if (bValid) {
                        var data = "id=" + idTipo + "&" + $("#frmCadastrarTipo").serialize();
                        $.ajax({
                            url: "cadastrarTipo.php",
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
                        tips.text("Informe o nome do tipo.");
                        $(this).dialog("destroy");
                    }
                },
                "Cancelar": function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome do tipo.");
                    $(this).dialog("destroy");
                }
            }
        });
    });

    $(document).on('click', '.excluirTipo', function() {
        var tipo = $(this).parent().parent()[0];
        var nomeTipo = tipo.cells[1].innerHTML;
        var idTipo = tipo.cells[2].getElementsByTagName("input")[0].value;

        $(".dialogConfirmTipo").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 320,
            modal: true,
            title: "EXCLUIR TIPO \"" + nomeTipo + "\"?",
            buttons: {
                "Excluir": function() {
                    $.ajax({
                        url: "excluirTipo.php",
                        type: "post",
                        data: "nomeTipo=" + nomeTipo + "&idTipo=" + idTipo,
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

    /* UNIDADE */
    $("#cadastrarUnidade").click(function() {
        var unidade = $("#unidade"), allFields = $([]).add(unidade), tips = $(".validateTips");
        $(".dialogFormUni").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 220,
            width: 350,
            modal: true,
            resizable: false,
            title: "CADASTRAR UNIDADE",
            buttons: {
                "Cadastrar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(unidade, "tipo", 1, 5, tips);

                    if (bValid) {
                        $.ajax({
                            url: "cadastrarUnidade.php",
                            type: "post",
                            data: $("#frmCadastrarUnidade").serialize(),
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
                        tips.text("Informe o nome da unidade.");
                        $(this).dialog("destroy");
                    }
                },
                "Cancelar": function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome da unidade.");
                    $(this).dialog("destroy");
                }
            }
        });
        $(".dialogFormUni").dialog("open");
    });

    $(document).on('click', '.editarUnidade', function() {
        var unidade, idUnidade, nomeUnidade, campoUnidade, allFields, tips;

        unidade = $(this).parent().parent()[0];
        idUnidade = unidade.cells[2].getElementsByTagName("input")[0].value;
        nomeUnidade = unidade.cells[1].innerHTML;
        campoUnidade = $("#unidade");
        allFields = $([]).add(campoUnidade);
        tips = $(".validateTips");
        campoUnidade.val(nomeUnidade);

        $(".dialogFormUni").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 220,
            width: 350,
            modal: true,
            title: "EDITAR UNIDADE",
            buttons: {
                "Editar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(campoUnidade, "tipo", 1, 5, tips);

                    if (bValid) {
                        var data = "id=" + idUnidade + "&" + $("#frmCadastrarUnidade").serialize();
                        $.ajax({
                            url: "cadastrarUnidade.php",
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
                        tips.text("Informe o nome da unidade.");
                        $(this).dialog("destroy");
                    }
                },
                "Cancelar": function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome da unidade.");
                    $(this).dialog("destroy");
                }
            }
        });
    });

    $(document).on('click', '.excluirUnidade', function() {
        var unidade = $(this).parent().parent()[0], nomeUnidade = unidade.cells[1].innerHTML;
        
        $(".dialogConfirmUni").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 320,
            modal: true,
            title: "EXCLUIR UNIDADE \"" + nomeUnidade + "\"?",
            buttons: {
                "Excluir": function() {
                    $.ajax({
                        url: "excluirUnidade.php",
                        type: "post",
                        data: "nomeUnidade=" + nomeUnidade,
                        success: function(retorno) {
                            var mensagem, excluiu = retorno.trim();
                            if (excluiu == "TRUE") {
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

    /* SERVICO */
    $("#cadastrarServ").click(function() {
        var servico = $("#servico"), allFields = $([]).add(servico), tips = $(".validateTips");
        $(".dialogFormServ").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 220,
            width: 350,
            modal: true,
            resizable: false,
            title: "CADASTRAR SERVIÇO",
            buttons: {
                "Cadastrar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(servico, "tipo", 1, 5, tips);

                    if (bValid) {
                        $.ajax({
                            url: "cadastrarServico.php",
                            type: "post",
                            data: $("#frmCadastrarServ").serialize(),
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
                        tips.text("Informe o nome do serviço.");
                        $(this).dialog("destroy");
                    }
                },
                "Cancelar": function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome do serviço");
                    $(this).dialog("destroy");
                }
            }
        });
        $(".dialogFormServ").dialog("open");
    });

    $(document).on('click', '.editarServico', function() {
        var servico, idServ, nomeServ, campoServ, allFields, tips;

        servico = $(this).parent().parent()[0];
        idServ = servico.cells[2].getElementsByTagName("input")[0].value;
        nomeServ = servico.cells[1].innerHTML;
        campoServ = $("#servico");
        allFields = $([]).add(campoServ);
        tips = $(".validateTips");
        campoServ.val(nomeServ);

        $(".dialogFormServ").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 220,
            width: 350,
            modal: true,
            title: "EDITAR SERVIÇO",
            buttons: {
                "Editar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(campoServ, "servico", 1, 5, tips);

                    if (bValid) {
                        var data = "id=" + idServ + "&" + $("#frmCadastrarServ").serialize();
                        $.ajax({
                            url: "cadastrarServico.php",
                            type: "post",
                            data: data,
                            success: function(retorno) {
                                var mensagem, editou = retorno.trim();
                                if (editou === "CADASTRADO") {
                                    mensagem = $("#msgCadastradoDialog");
                                    updateMessage(mensagem);
                                } else if (editou === "TRUE") {
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
                        tips.text("Informe o nome do serviço.");
                        $(this).dialog("destroy");
                    }
                },
                "Cancelar": function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome do serviço.");
                    $(this).dialog("destroy");
                }
            }
        });
    });

    $(document).on('click', '.excluirServico', function() {
        var servico = $(this).parent().parent()[0];
        var nomeServ = servico.cells[1].innerHTML;
        var idServ = servico.cells[2].getElementsByTagName("input")[0].value;

        $(".dialogConfirmServ").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 320,
            modal: true,
            title: "EXCLUIR SERVIÇO \"" + nomeServ + "\"?",
            buttons: {
                "Excluir": function() {
                    $.ajax({
                        url: "excluirServico.php",
                        type: "post",
                        data: "nomeServ=" + nomeServ + "&idServ=" + idServ,
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
});