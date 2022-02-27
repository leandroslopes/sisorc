$(function() {
    $.noConflict();

    /* COMPOSICAO */
    $("#pesquisarComp").click(function() {
        var descPesquisa = $('#descPesquisa'), selectComp = $('#compPesquisa');

        $("#dialogFormPesquisarComp").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 370,
            width: 630,
            modal: true,
            resizable: false,
            title: "PESQUISAR COMPOSIÇÃO",
            buttons: {
                Cancelar: function() {
                    descPesquisa.val("");
                    selectComp.html("");
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogFormPesquisarComp").dialog("open");
    });

    $("#cadastrarComp").click(function() {
        var descricao = $("#descricao"), allFields;
        var tips = $(".validateTips");

        allFields = $([]).add(descricao);

        $("#dialogFormComp").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 340,
            width: 450,
            modal: true,
            resizable: false,
            title: "CADASTRAR COMPOSIÇÃO",
            buttons: {
                Cadastrar: function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(descricao, "", 5, 150, tips);

                    if (bValid) {
                        $.ajax({
                            url: "cadastrarComp.php",
                            type: "post",
                            data: $("#frmCadastrarComp").serialize(),
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
                        tips.text("Informe o nome da composição.");
                        $(this).dialog("destroy");
                    }
                },
                Cancelar: function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome da composição.");
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogFormComp").dialog("open");
    });

    $("#editarComp").click(function() {
        var codComp = $('#codComp').val(), descricao = $('#descricao'), allFields, tips, aviso;

        aviso = $('.aviso');
        aviso.css('display', 'block');

        allFields = $([]).add(descricao);
        tips = $('.validateTips');

        $("#dialogFormComp").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 370,
            width: 450,
            modal: true,
            title: "EDITAR COMPOSIÇÃO",
            buttons: {
                Editar: function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(descricao, "descricao", 5, 150, tips);

                    if (bValid) {
                        var data = "codigo=" + codComp + "&" + $("#frmCadastrarComp").serialize();
                        $.ajax({
                            url: "cadastrarComp.php",
                            type: "post",
                            data: data,
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
                        allFields.removeClass("ui-state-error");
                        tips.text("Informe o nome da composição.");
                        aviso.css('display', 'none');
                        $(this).dialog("destroy");
                    }
                },
                Cancelar: function() {
                    allFields.removeClass("ui-state-error");
                    tips.text("Informe o nome da composição.");
                    aviso.css('display', 'none');
                    $(this).dialog("destroy");
                }
            }
        });
    });

    /* INSUMO */
    $("#pesquisarIns").click(function() {
        var insDescPesquisa = $('#insDescPesquisa'), selectIns = $('#insPesquisa');

        $("#dialogFormPesquisarIns").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 370,
            width: 630,
            modal: true,
            resizable: false,
            title: "PESQUISAR INSUMO",
            buttons: {
                Cancelar: function() {
                    insDescPesquisa.val("");
                    selectIns.html("");
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogFormPesquisarIns").dialog("open");
    });

    $("#cadastrarIns").click(function() {
        var descricao = $("#desc_ins"), unidade = $("#uni_ins"), servico = $("#serv_ins"), tipo = $("#tipo_ins"), allFields;
        allFields = $([]).add(descricao);
        allFields = $([]).add(unidade);
        allFields = $([]).add(servico);
        allFields = $([]).add(tipo);
        var tips = $(".validateTips");

        $("#dialogFormIns").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 360,
            width: 420,
            modal: true,
            resizable: false,
            title: "CADASTRAR INSUMO",
            buttons: {
                Cadastrar: function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(descricao, "", 5, 150, tips);

                    if (bValid) {
                        $.ajax({
                            url: "cadastrarIns.php",
                            type: "post",
                            data: $("#frmCadastrarIns").serialize(),
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
                        tips.text("Informe o descrição do insumo.");
                        $(this).dialog("destroy");
                    }
                },
                Cancelar: function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o descrição do insumo.");
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogFormIns").dialog("open");
    });

    $("#adicionarIns").click(function() {
        var insDescAdd = $('#insDescAdd'), selectIns = $('#insPesquisa'), insumo = $('#insumo');

        $(".dialogFormAddIns").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 370,
            width: 630,
            modal: true,
            resizable: false,
            title: "ADICIONAR INSUMO",
            buttons: {
                Adicionar: function() {
                    if (insumo.html() !== "") {
                        $.ajax({
                            url: "adicionarIns.php",
                            type: "post",
                            data: $("#frmAdicionarIns").serialize(),
                            success: function(retorno) {
                                var mensagem, adicionou = retorno.trim();
                                if (adicionou === "CADASTRADO") {
                                    mensagem = $("#msgCadastradoDialog");
                                    updateMessage(mensagem);
                                } else if (adicionou === "TRUE") {
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
                        insDescAdd.val("");
                        selectIns.html("");
                        insumo.html("");
                        $(this).dialog("destroy");
                    }
                },
                Cancelar: function() {
                    insDescAdd.val("");
                    selectIns.html("");
                    insumo.html("");
                    $(this).dialog("destroy");
                }
            }
        });
        $(".dialogFormAddIns").dialog("open");
    });

    $(document).on("click", ".excluirAddIns", function() {
        var insumo = $(this).parent().parent()[0];
        var idCompIns = insumo.cells[0].getElementsByTagName("input")[0].value;
        var descCompIns = insumo.cells[1].getElementsByTagName("input")[0].value;

        var msg = "Tem certeza que quer excluir " + descCompIns + " desta composi&ccedil;&atilde;o?";

        $("#msgExcluir").html(msg);

        $(".dialogConfirmIns").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 190,
            width: 320,
            modal: true,
            title: "EXCLUIR INSUMO?",
            buttons: {
                Excluir: function() {
                    $.ajax({
                        url: "excluirAddIns.php",
                        type: "post",
                        data: "id=" + idCompIns,
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
                Cancelar: function() {
                    $(this).dialog("destroy");
                }
            }
        });
    });

    $(document).on("click", ".editarIns", function() {
        var insumo, codIns, descricao, campoDescricao, unidade, selectUni, servico, selectServ, tipo, selectTipo, allFields, tips;

        insumo = $(this).parent().parent()[0];
        codIns = insumo.cells[0].getElementsByTagName("input")[0].value;
        descricao = insumo.cells[1].innerHTML;
        unidade = insumo.cells[2].getElementsByTagName("input")[0].value;
        servico = insumo.cells[3].getElementsByTagName("input")[0].value;
        tipo = insumo.cells[4].getElementsByTagName("input")[0].value;

        campoDescricao = $('#desc_ins');
        selectUni = $('#uni_ins');
        selectServ = $('#serv_ins');
        selectTipo = $('#tipo_ins');

        allFields = $([]).add(campoDescricao);
        tips = $(".validateTips");

        campoDescricao.val(descricao);
        selectUni.val(unidade);
        selectServ.val(servico);
        selectTipo.val(tipo);

        $("#dialogFormIns").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 330,
            width: 420,
            modal: true,
            title: "EDITAR INSUMO",
            buttons: {
                Editar: function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(campoDescricao, "desc_ins", 5, 150, tips);

                    if (bValid) {
                        var data = "codigo=" + codIns + "&" + $("#frmCadastrarIns").serialize();
                        $.ajax({
                            url: "cadastrarIns.php",
                            type: "post",
                            data: data,
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
                        tips.text("Informe o nome do insumo.");
                        $(this).dialog("destroy");
                    }
                },
                Cancelar: function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome do insumo.");
                    $(this).dialog("destroy");
                }
            }
        });
    });

    /* ORCAMENTO */
    $(document).on('click', '.excluirOrc', function() {
        var orcamento = $(this).parent().parent()[0], idOrc = orcamento.cells[5].getElementsByTagName("input")[0].value;

        $(".dialogConfirm").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 160,
            width: 320,
            modal: true,
            title: "EXCLUIR ORÇAMENTO?",
            buttons: {
                "Excluir": function() {
                    $.ajax({
                        url: "excluirOrc.php",
                        type: "post",
                        data: "id=" + idOrc,
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

    $("#editarBDI").click(function() {
        var bdi = $("#bdi"), allFields = $([]).add(bdi), tips = $(".validateTips");

        $("#dialogFormBDI").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 190,
            width: 350,
            modal: true,
            resizable: false,
            title: "EDITAR B. D. I.",
            buttons: {
                "Cadastrar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(bdi, "bdi", 2, 6, tips);

                    if (bValid) {
                        $.ajax({
                            url: "editarBDI.php",
                            type: "post",
                            data: $("#frmEditarBDI").serialize(),
                            success: function(retorno) {
                                var mensagem, cadastrou = retorno.trim();
                                if (cadastrou === "TRUE") {
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
                        $(this).dialog("destroy");
                    }
                },
                "Cancelar": function() {
                    allFields.val("").removeClass("ui-state-error");
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogFormBDI").dialog("open");
    });

    $("#editarES").click(function() {
        var encargo_social = $("#encargo_social"), allFields = $([]).add(encargo_social), tips = $(".validateTips");

        $("#dialogFormES").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 190,
            width: 350,
            modal: true,
            resizable: false,
            title: "EDITAR ENCARGOS SOCIAIS",
            buttons: {
                "Cadastrar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(encargo_social, "encargo_social", 2, 6, tips);

                    if (bValid) {
                        $.ajax({
                            url: "editarES.php",
                            type: "post",
                            data: $("#frmEditarES").serialize(),
                            success: function(retorno) {
                                var mensagem, cadastrou = retorno.trim();
                                if (cadastrou === "TRUE") {
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
                        $(this).dialog("destroy");
                    }
                },
                "Cancelar": function() {
                    allFields.val("").removeClass("ui-state-error");
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogFormES").dialog("open");
    });

    $("#adicionarComp").click(function() {
        var compCod = $('#compCod'), compDesc = $('#compDesc'), selectComp = $('#composicoes'), composicao = $('#composicao'), selectIns = $('#insumos');

        $("#dialogFormAddComp").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 530,
            width: 630,
            modal: true,
            resizable: false,
            title: "ADICIONAR COMPOSIÇÃO",
            buttons: {
                "Adicionar": function() {

                    $.ajax({
                        url: "adicionarComp.php",
                        type: "post",
                        data: $("#frmAdicionarComp").serialize(),
                        success: function(retorno) {
                            var mensagem, adicionou = retorno.trim();
                            if (adicionou === "CADASTRADO") {
                                mensagem = $("#msgCadastradoDialog");
                                updateMessage(mensagem);
                            } else if (adicionou === "TRUE") {
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
                    compCod.val("");
                    compDesc.val("");
                    selectComp.html("");
                    composicao.html("");
                    selectIns.html("");
                    $(this).dialog("destroy");
                },
                "Cancelar": function() {
                    compCod.val("");
                    compDesc.val("");
                    selectComp.html("");
                    composicao.html("");
                    selectIns.html("");
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogFormAddComp").dialog("open");
    });

    $('#copiarComp').click(function() {
        var idOrc, nomeObra, selectObras, obra, selectComp, composicao;
        idOrc = $('#id_orc_cop').val(), nomeObra = $('#nome_obra'), selectObras = $('#obras'), obra = $('#obra');
        selectComp = $('#composicoes_cop'), composicao = $('#composicao_cop');

        $('#dialogFormCopComp').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 560,
            width: 630,
            modal: true,
            resizable: false,
            title: 'COPIAR COMPOSIÇÃO',
            buttons: {
                "Adicionar": function() {
                    if (composicao.html() !== "") {
                        $.ajax({
                            url: 'copiarComp.php',
                            type: 'post',
                            data: {idOrc: idOrc, idOrcComp: selectComp.val()},
                            success: function(retorno) {
                                var mensagem, copiou = retorno.trim();
                                if (copiou === "CADASTRADO") {
                                    mensagem = $('#msgCadastradoDialog');
                                    updateMessage(mensagem);
                                } else if (copiou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    nomeObra.val('');
                    selectObras.html('');
                    obra.html('');
                    selectComp.html('');
                    composicao.html('');
                    $(this).dialog('destroy');
                },
                "Cancelar": function() {
                    nomeObra.val('');
                    selectObras.html('');
                    obra.html('');
                    selectComp.html('');
                    composicao.html('');
                    $(this).dialog('destroy');
                }
            }
        });
        $("#dialogFormCopComp").dialog('open');
    });

    $("#cadastrarNovaComp").click(function() {
        var descricao = $("#descricao"), allFields;
        var tips = $(".validateTips");

        allFields = $([]).add(descricao);

        $("#dialogFormComp").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 340,
            width: 450,
            modal: true,
            resizable: false,
            title: "CADASTRAR COMPOSIÇÃO",
            buttons: {
                Cadastrar: function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");

                    bValid = bValid && checkLength(descricao, "", 5, 150, tips);

                    if (bValid) {
                        $.ajax({
                            url: "cadastrarNovaComp.php",
                            type: "post",
                            data: $("#frmCadastrarComp").serialize(),
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
                        tips.text("Informe o nome da composição.");
                        $(this).dialog("destroy");
                    }
                },
                Cancelar: function() {
                    allFields.val("").removeClass("ui-state-error");
                    tips.text("Informe o nome da composição.");
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogFormComp").dialog("open");
    });

    $(document).on("click", ".adicionarSubcomp", function() {
        var idOrc, idOrcComp, orcComp;
        idOrc = $('#id_orc').val();
        orcComp = ('.adicionarSubcomp');
        orcComp = $(this).parent().parent()[0];
        idOrcComp = orcComp.cells[0].getElementsByTagName("input")[0].value;

        $.ajax({
            type: 'post',
            url: '../../scripts/ajax.php',
            data: {tipo: 'subcomposicao', idOrc: idOrc, idOrcComp: idOrcComp},
            dataType: 'json',
            success: function(subcomposicoes) {
                if (subcomposicoes !== "") {
                    var opcoes = "";
                    $.each(subcomposicoes, function() {
                        $.each(this, function(index, valor) {
                            opcoes += "<option value='" + index + "'>" + index + " - " + valor + "</option>";
                        });
                    });
                    $('#subcomposicao').html(opcoes);
                } else {
                    var opcao = "<option value=''>Nenhuma composi&ccedil;&atilde;o encontrada</option>";
                    $('#subcomposicao').html(opcao);
                }
            }
        });

        $('#dialogFormSubComp').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 270,
            width: 630,
            modal: true,
            resizable: false,
            title: 'ADICIONAR SUBCOMPOSIÇÃO',
            buttons: {
                "Cadastrar": function() {
                    var idOrcSubcomp, quantidade;
                    idOrcSubcomp = $('#subcomposicao').val();
                    quantidade = $('#qtd_subcomp').val();

                    $.ajax({
                        type: 'post',
                        url: 'adicionarSubcomp.php',
                        data: {idOrcComp: idOrcComp, idOrcSubcomp: idOrcSubcomp, quantidade: quantidade},
                        success: function(retorno) {
                            var mensagem, cadastrou = retorno.trim();
                            if (cadastrou === "TRUE") {
                                mensagem = $('#msgSucessoDialog');
                                updateMessage(mensagem);
                            } else {
                                mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        },
                        error: function() {
                            var mensagem = $('#msgErroDialog');
                            updateMessage(mensagem);
                        }
                    });
                    $(this).dialog('destroy');
                },
                "Cancelar": function() {
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormSubComp').dialog('open');
    });

    $('.eddOrcCompSub').click(function() {
        var id, idSubComp, orcSubComp, quantidade;

        orcSubComp = $(this).parent().parent()[0];
        id = orcSubComp.cells[0].getElementsByTagName('input')[0].value;
        idSubComp = $('#id_subcomp');
        idSubComp.val(id);
        quantidade = $('#qtd_subcomp2');

        $('#dialogFormSubcomp2').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 190,
            width: 350,
            modal: true,
            resizable: false,
            title: 'EDITAR SUBCOMPOSIÇÃO',
            buttons: {
                "Cadastrar": function() {
                    if (quantidade.val() !== "") {
                        $.ajax({
                            url: "editarSubcomp.php",
                            type: "post",
                            data: $("#frmEddOrcCompSub").serialize(),
                            success: function(retorno) {
                                var mensagem, cadastrou = retorno.trim();
                                if (cadastrou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    idSubComp.val("");
                    quantidade.val("");
                    $(this).dialog('destroy');
                },
                "Cancelar": function() {
                    idSubComp.val("");
                    quantidade.val("");
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormSubcomp2').dialog('open');
    });

    $(document).on('click', '.excOrcCompSub', function() {
        var subcomposicao, id;
        subcomposicao = $(this).parent().parent()[0], id = subcomposicao.cells[0].getElementsByTagName("input")[1].value;

        $('#dialogConfirmSubComp').dialog({
            dialogClass: 'no-close',
            resizable: false,
            height: 160,
            width: 300,
            modal: true,
            title: 'EXCLUIR SUBCOMPOSIÇÃO?',
            buttons: {
                "Excluir": function() {
                    $.ajax({
                        url: 'excluirSubcomp.php',
                        type: 'post',
                        data: {id: id},
                        success: function(retorno) {
                            var mensagem, excluiu = retorno.trim();
                            if (excluiu === 'CADASTRADO') {
                                mensagem = $('#msgProibicaoDialog');
                                updateMessage(mensagem);
                            } else if (excluiu === "TRUE") {
                                mensagem = $('#msgSucessoDialog');
                                updateMessage(mensagem);
                            } else {
                                mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        }
                    });
                    $(this).dialog('destroy');
                },
                "Cancelar": function() {
                    $(this).dialog('destroy');
                }
            }
        });
    });

    $(".addOrcCompIns").click(function() {
        var id, idOrcComp, servico, insDesc, insAdd, insumo, composicao;
        idOrcComp = $('#id_orc_comp'), servico = $('#serv_ins'), insDesc = $("#insDesc"), insAdd = $('#insAdd'), insumo = $('#insumo');

        composicao = $(this).parent().parent()[0];
        id = composicao.cells[0].getElementsByTagName('input')[0].value;
        idOrcComp.val(id);

        $("#dialogFormAddOrcCompIns").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 540,
            width: 630,
            modal: true,
            resizable: false,
            title: "ADICIONAR INSUMO",
            buttons: {
                "Adicionar": function() {

                    if (insumo.html() !== "") {
                        $.ajax({
                            url: 'addOrcCompIns.php',
                            type: 'post',
                            data: $('#frmAddOrcCompIns').serialize(),
                            success: function(retorno) {
                                var mensagem, adicionou = retorno.trim();
                                if (adicionou === "CADASTRADO") {
                                    mensagem = $('#msgCadastradoDialog');
                                    updateMessage(mensagem);
                                } else if (adicionou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    idOrcComp.val('');
                    insDesc.val('');
                    insAdd.html('');
                    insumo.html('');
                    $(this).dialog('destroy');
                },
                "Cancelar": function() {
                    idOrcComp.val('');
                    insDesc.val('');
                    insAdd.html('');
                    insumo.html('');
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormAddOrcCompIns').dialog('open');
    });

    $('.editarOrcCompIns').click(function() {
        var orcCompIns, id, quantidade = $('#insQtd'), preco = $('#insPreco');

        orcCompIns = $(this).parent().parent()[0];
        id = orcCompIns.cells[0].getElementsByTagName('input')[0].value;
        $('#insId').val(id);

        $('#dialogFormEditarIns').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 230,
            width: 350,
            modal: true,
            resizable: false,
            title: 'EDITAR INSUMO',
            buttons: {
                "Cadastrar": function() {

                    if ((quantidade.val() !== "" || preco.val() !== "")) {
                        $.ajax({
                            url: 'editarOrcCompIns.php',
                            type: 'post',
                            data: $('#frmEditarOrcCompIns').serialize(),
                            success: function(retorno) {
                                var mensagem, editou = retorno.trim();
                                if (editou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    quantidade.val('');
                    preco.val('');
                    $(this).dialog('destroy');
                },
                "Cancelar": function() {
                    quantidade.val('');
                    preco.val('');
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormEditarIns').dialog('open');
    });

    $(document).on("click", ".excluirOrcComp", function() {
        var composicao = $(this).parent().parent()[0];
        var idOrc = composicao.cells[0].getElementsByTagName("input")[1].value;
        var idComp = composicao.cells[0].getElementsByTagName("input")[2].value;

        $("#dialogConfirmComp").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 160,
            width: 320,
            modal: true,
            title: "EXCLUIR COMPOSIÇÃO?",
            buttons: {
                Excluir: function() {
                    $.ajax({
                        url: "excluirOrcComp.php",
                        type: "post",
                        data: "idOrc=" + idOrc + "&idComp=" + idComp,
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
                Cancelar: function() {
                    $(this).dialog("destroy");
                }
            }
        });
    });

    $(document).on("click", ".editarDescComp", function() {
        var composicao, id_orc_comp, codigo, descricao, id_modulo, id_orc, url;

        composicao = $(this).parent().parent()[0];
        id_orc_comp = composicao.cells[0].getElementsByTagName("input")[0].value;
        codigo = composicao.cells[1].getElementsByTagName("label")[0].innerHTML;
        descricao = composicao.cells[1].getElementsByTagName("label")[1].innerHTML;

        $("#descricao_edd").val(descricao);
        id_modulo = $("#id_modulo_edd_desc").val();
        id_orc = composicao.cells[0].getElementsByTagName("input")[1].value;

        url = "composicao_custo.php?id=" + id_modulo + "&id_orc=" + id_orc;

        $("#dialogFromEddDescComp").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 220,
            width: 500,
            modal: true,
            title: "EDITAR DESCRIÇÃO",
            buttons: {
                "Editar": function() {
                    $.ajax({
                        url: "editarDescComp.php",
                        type: "post",
                        data: "id_orc=" + id_orc + "&id_orc_comp=" + id_orc_comp + "&cod_comp_antigo=" + codigo
                                + "&descricao=" + $("#descricao_edd").val(),
                        success: function(retorno) {
                            var editou = retorno.trim();
                            if (editou === "CADASTRADO") {
                                showMsgCadastrado(url);
                            } else if (editou === "TRUE") {
                                showMsgSucesso(url);
                            } else {
                                showMsgErro(url);
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
        $("#dialogFromEddDescComp").dialog("open");
    });

    /* TITULO */
    $('#cadastrarTitulo').click(function() {
        var idOrc = $('#id_orc_cad').val(), nome = $('#nome_cad'), item = $('#item_cad');

        $('#dialogFormCadTitulo').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 230,
            width: 520,
            modal: true,
            resizable: false,
            title: 'CADASTRAR TÍTULO',
            buttons: {
                "Cadastrar": function() {
                    if (nome.val() !== "" && item.val() !== "") {
                        $.ajax({
                            url: 'cadastrarTitulo.php',
                            type: 'post',
                            data: {idOrc: idOrc, nome: nome.val(), item: item.val()},
                            success: function(retorno) {
                                var mensagem, cadastrou = retorno.trim();
                                if (cadastrou === "CADASTRADO") {
                                    mensagem = $('#msgCadastradoDialog');
                                    updateMessage(mensagem);
                                } else if (cadastrou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    nome.val('');
                    item.val('');
                    $(this).dialog('destroy');
                },
                "Cancelar": function() {
                    nome.val('');
                    item.val('');
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormCadTitulo').dialog('open');
    });

    $('#adicionarTitulo').click(function() {
        var idOrc = $('#id_orc_add').val(), selectTitulos = $('#titulos'), nomePesq = $('#nome_pesq'), titulo = $('#titulo'), item = $('#item_add');

        $('#dialogFormAddTitulo').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 420,
            width: 630,
            modal: true,
            resizable: false,
            title: 'ADICIONAR TÍTULO',
            buttons: {
                Adicionar: function() {
                    if (titulo.html() !== "" && item.val() !== "") {
                        $.ajax({
                            url: 'adicionarTitulo.php',
                            type: 'post',
                            data: {idOrc: idOrc, nome: selectTitulos.val(), item: item.val()},
                            success: function(retorno) {
                                var mensagem, adicionou = retorno.trim();
                                if (adicionou === "CADASTRADO") {
                                    mensagem = $('#msgCadastradoDialog');
                                    updateMessage(mensagem);
                                } else if (adicionou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    selectTitulos.html('');
                    nomePesq.val('');
                    titulo.html('');
                    item.val('');
                    $(this).dialog('destroy');
                },
                Cancelar: function() {
                    selectTitulos.html('');
                    nomePesq.val('');
                    titulo.html('');
                    item.val('');
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormAddTitulo').dialog('open');
    });

    $(document).on('click', '.eddTitulo', function() {
        var idOrc, id, nome, item, titulo, nomeEdd, itemEdd;

        idOrc = $('#id_orc_add').val(), nomeEdd = $('#nome_edd'), itemEdd = $('#item_edd');

        titulo = $(this).parent().parent()[0];
        id = titulo.cells[0].getElementsByTagName('input')[0].value;
        nome = titulo.cells[1].getElementsByTagName('a')[0].innerHTML;
        item = titulo.cells[0].getElementsByTagName('label')[0].innerHTML;

        $('#id_orc_tit_edd').val(id);
        nomeEdd.val(nome);
        itemEdd.val(item);

        $('#dialogFormEddTitulo').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 230,
            width: 520,
            modal: true,
            resizable: false,
            title: 'EDITAR TÍTULO',
            buttons: {
                Editar: function() {
                    if (nomeEdd.val() !== "" && itemEdd.val()) {
                        $.ajax({
                            url: 'editarTitulo.php',
                            type: 'post',
                            data: {idOrc: idOrc, idTit: id, nome: nomeEdd.val(), item: itemEdd.val()},
                            success: function(retorno) {
                                var mensagem, adicionou = retorno.trim();
                                if (adicionou === "CADASTRADO") {
                                    mensagem = $('#msgCadastradoDialog');
                                    updateMessage(mensagem);
                                } else if (adicionou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    nomeEdd.val('');
                    itemEdd.val('');
                    $(this).dialog('destroy');
                },
                Cancelar: function() {
                    nomeEdd.val('');
                    itemEdd.val('');
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormEddTitulo').dialog('open');
    });

    $('#adicionarTitComp').click(function() {
        var item = $('#tit_comp_item_add'), quantidade = $('#qtd_add');

        $('#dialogFormAddTitComp').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 380,
            width: 620,
            modal: true,
            resizable: false,
            title: 'ADICIONAR COMPOSIÇÃO',
            buttons: {
                Adicionar: function() {
                    if (item.val() !== '' && quantidade.val() !== '') {
                        $.ajax({
                            url: 'adicionarTitComp.php',
                            type: 'post',
                            data: $('#frmAdicionarTitComp').serialize(),
                            success: function(retorno) {
                                var mensagem, adicionou = retorno.trim();
                                if (adicionou === "CADASTRADO") {
                                    mensagem = $('#msgCadastradoDialog');
                                    updateMessage(mensagem);
                                } else if (adicionou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    item.val('');
                    quantidade.val('');
                    $(this).dialog('destroy');
                },
                Cancelar: function() {
                    item.val('');
                    quantidade.val('');
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormAddTitComp').dialog('open');
    });

    $(document).on('click', '.excTitulo', function() {
        var titulo = $(this).parent().parent()[0], id = titulo.cells[0].getElementsByTagName("input")[0].value;

        $('#dialogConfirmTitulo').dialog({
            dialogClass: 'no-close',
            resizable: false,
            height: 160,
            width: 320,
            modal: true,
            title: "EXCLUIR TÍTULO?",
            buttons: {
                "Excluir": function() {
                    $.ajax({
                        url: "excluirTitulo.php",
                        type: "post",
                        data: "id=" + id,
                        success: function(retorno) {
                            var mensagem, excluiu = retorno.trim();
                            if (excluiu === "RELACIONADO") {
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

    /* SUBTITULO */
    $('#cadastrarSubTitulo').click(function() {
        var selectTitulos = $('#orc_titulos'), nome = $('#subtit_cad'), item = $('#subitem_cad');

        $('#dialogFormCadSubTit').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 320,
            width: 625,
            modal: true,
            resizable: false,
            title: 'CADASTRAR SUBTÍTULO',
            buttons: {
                "Cadastrar": function() {
                    if (nome.val() !== "" && item.val() !== "") {
                        $.ajax({
                            url: 'cadastrarSubtitulo.php',
                            type: 'post',
                            data: {idOrcTit: selectTitulos.val(), nome: nome.val(), item: item.val()},
                            success: function(retorno) {
                                var mensagem, cadastrou = retorno.trim();
                                if (cadastrou === "CADASTRADO") {
                                    mensagem = $('#msgCadastradoDialog');
                                    updateMessage(mensagem);
                                } else if (cadastrou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    nome.val('');
                    item.val('');
                    $(this).dialog('destroy');
                },
                "Cancelar": function() {
                    nome.val('');
                    item.val('');
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormCadSubTit').dialog('open');
    });

    $('#adicionarSubTitulo').click(function() {
        var nomePesq = $('#subtit_pesq'), selectSubtitulos = $('#subtitulos'), nome = $('#subtitulo'), item = $('#subitem_add');

        $('#dialogFormAddSubTit').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 510,
            width: 630,
            modal: true,
            resizable: false,
            title: 'ADICIONAR SUBTÍTULO',
            buttons: {
                Adicionar: function() {
                    if (nome.html() !== "" && item.val() !== "") {
                        $.ajax({
                            url: 'adicionarSubtitulo.php',
                            type: 'post',
                            data: $('#frmAdicionarSubTit').serialize(),
                            success: function(retorno) {
                                var mensagem, adicionou = retorno.trim();
                                if (adicionou === "CADASTRADO") {
                                    mensagem = $('#msgCadastradoDialog');
                                    updateMessage(mensagem);
                                } else if (adicionou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    nomePesq.val('');
                    selectSubtitulos.html('');
                    nome.html('');
                    item.val('');
                    $(this).dialog('destroy');
                },
                Cancelar: function() {
                    nomePesq.val('');
                    selectSubtitulos.html('');
                    nome.html('');
                    item.val('');
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormAddSubTit').dialog('open');
    });

    $(document).on('click', '.eddSubtit', function() {
        var id, nome, item, subtitulo, nomeEdd, itemEdd;

        nomeEdd = $('#subnome_edd'), itemEdd = $('#subitem_edd');

        subtitulo = $(this).parent().parent()[0];
        id = subtitulo.cells[0].getElementsByTagName('input')[0].value;
        nome = subtitulo.cells[1].getElementsByTagName('a')[0].innerHTML;
        item = subtitulo.cells[0].getElementsByTagName('label')[0].innerHTML;

        $('#id_subtit_edd').val(id);
        nomeEdd.val(nome);
        itemEdd.val(item);

        $('#dialogFormEddSubTit').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 230,
            width: 520,
            modal: true,
            resizable: false,
            title: 'EDITAR SUBTÍTULO',
            buttons: {
                Editar: function() {
                    if (nomeEdd.val() !== "" && itemEdd.val() !== "") {
                        $.ajax({
                            url: 'editarSubtitulo.php',
                            type: 'post',
                            data: {id: id, nome: nomeEdd.val(), item: itemEdd.val()},
                            success: function(retorno) {
                                var mensagem, adicionou = retorno.trim();
                                if (adicionou === "CADASTRADO") {
                                    mensagem = $('#msgCadastradoDialog');
                                    updateMessage(mensagem);
                                } else if (adicionou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    nomeEdd.val('');
                    itemEdd.val('');
                    $(this).dialog('destroy');
                },
                Cancelar: function() {
                    nomeEdd.val('');
                    itemEdd.val('');
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormEddSubTit').dialog('open');
    });

    $(document).on('click', '.excSubtit', function() {
        var subtitulo = $(this).parent().parent()[0], id = subtitulo.cells[0].getElementsByTagName('input')[0].value;

        $('#dialogConfirmSubtit').dialog({
            dialogClass: 'no-close',
            resizable: false,
            height: 160,
            width: 320,
            modal: true,
            title: 'EXCLUIR SUBTÍTULO?',
            buttons: {
                Excluir: function() {
                    $.ajax({
                        url: 'excluirSubtitulo.php',
                        type: 'post',
                        data: 'id=' + id,
                        success: function(retorno) {
                            var mensagem, excluiu = retorno.trim();
                            if (excluiu === "RELACIONADO") {
                                mensagem = $('#msgProibicaoDialog');
                                updateMessage(mensagem);
                            } else if (excluiu === "TRUE") {
                                mensagem = $('#msgSucessoDialog');
                                updateMessage(mensagem);
                            } else {
                                mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        }
                    });
                    $(this).dialog('destroy');
                },
                Cancelar: function() {
                    $(this).dialog('destroy');
                }
            }
        });
    });

    $('#adicionarSubtitComp').click(function() {
        var item = $('#item_add2'), quantidade = $('#qtd_add2');

        $('#dialogFormAddSubtitComp').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 380,
            width: 620,
            modal: true,
            resizable: false,
            title: 'ADICIONAR COMPOSIÇÃO',
            buttons: {
                Adicionar: function() {
                    if (item.val() !== '' && quantidade.val() !== '') {
                        $.ajax({
                            url: 'adicionarSubtitComp.php',
                            type: 'post',
                            data: $('#frmAdicionarSubtitComp').serialize(),
                            success: function(retorno) {
                                var mensagem, adicionou = retorno.trim();
                                if (adicionou === "CADASTRADO") {
                                    mensagem = $('#msgCadastradoDialog');
                                    updateMessage(mensagem);
                                } else if (adicionou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    item.val('');
                    quantidade.val('');
                    $(this).dialog('destroy');
                },
                Cancelar: function() {
                    item.val('');
                    quantidade.val('');
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormAddSubtitComp').dialog('open');
    });

    $(document).on('click', '.eddTitComp, .eddSubtitComp', function() {
        var id /*TITCOMP OU SUBTITCOMP*/, idOrcComp, item, quantidade, composicao, tipoComp, url;

        id = $('#id_comp_edd'), idOrcComp = $('#id_orc_comp_edd'), item = $('#comp_item_edd'), quantidade = $('#qtd_edd');

        composicao = $(this).parent().parent()[0];
        idOrcComp.val(composicao.cells[0].getElementsByTagName('input')[0].value);
        id.val(composicao.cells[0].getElementsByTagName('input')[1].value);
        item.val(composicao.cells[0].getElementsByTagName('label')[0].innerHTML);
        quantidade.val(composicao.cells[3].innerHTML);
        tipoComp = composicao.cells[0].getElementsByTagName('input')[2].value;

        if (tipoComp === 'tit_comp')
            url = "editarTitComp.php";
        else
            url = "editarSubtitComp.php";

        $('#dialogFormEddComp').dialog({
            dialogClass: 'no-close',
            autoOpen: false,
            height: 230,
            width: 320,
            modal: true,
            resizable: false,
            title: 'EDITAR COMPOSIÇÃO',
            buttons: {
                Editar: function() {
                    if (item.val() !== "" && quantidade.val() !== "") {
                        $.ajax({
                            url: url,
                            type: 'post',
                            data: {id: id.val(), idOrcComp: idOrcComp.val(), item: item.val(), quantidade: quantidade.val()},
                            success: function(retorno) {
                                var mensagem, editou = retorno.trim();
                                if (editou === "CADASTRADO") {
                                    mensagem = $('#msgCadastradoDialog');
                                    updateMessage(mensagem);
                                } else if (editou === "TRUE") {
                                    mensagem = $('#msgSucessoDialog');
                                    updateMessage(mensagem);
                                } else {
                                    mensagem = $('#msgErroDialog');
                                    updateMessage(mensagem);
                                }
                            },
                            error: function() {
                                var mensagem = $('#msgErroDialog');
                                updateMessage(mensagem);
                            }
                        });
                    } else {
                        var mensagem = $('#msgErroDialog');
                        updateMessage(mensagem);
                    }
                    item.val('');
                    quantidade.val('');
                    $(this).dialog('destroy');
                },
                Cancelar: function() {
                    item.val('');
                    quantidade.val('');
                    $(this).dialog('destroy');
                }
            }
        });
        $('#dialogFormEddComp').dialog('open');
    });

    $(document).on("click", ".excTitComp, .excSubtitComp", function() {
        var composicao, id, tipoComp;

        composicao = $(this).parent().parent()[0];
        id = composicao.cells[0].getElementsByTagName("input")[1].value;
        tipoComp = composicao.cells[0].getElementsByTagName("input")[2].value;

        $("#dialogConfirmExcComp").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 160,
            width: 320,
            modal: true,
            title: "EXCLUIR COMPOSIÇÃO?",
            buttons: {
                Excluir: function() {
                    $.ajax({
                        url: "excluirComposicao.php",
                        type: "post",
                        data: {id: id, tipo: tipoComp},
                        success: function(retorno) {
                            var mensagem, excluiu = retorno.trim();
                            if (excluiu === "TRUE") {
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
                Cancelar: function() {
                    $(this).dialog("destroy");
                }
            }
        });
    });

    /* CRONOGRAMA */
    $("#gerarCronograma").click(function() {
        $("#dialogFormGerarCronograma").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 230,
            width: 380,
            modal: true,
            resizable: false,
            title: "GERAR CRONOGRAMA",
            buttons: {
                Gerar: function() {
                    var idMod, idOrc, idCabecalho, quantidade, url;

                    idMod = $("#id_modulo").val();
                    idOrc = $("#id_orc").val();
                    idCabecalho = $("#cabecalho").val();
                    quantidade = $("#quantidade").val();
                    url = "cronograma.php?id=" + idMod + "&id_orc=" + idOrc + "&id_cab=" + idCabecalho + "&qtd=" + quantidade;

                    $(location).attr("href", url);
                    $(this).dialog("destroy");
                },
                Cancelar: function() {
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogFormGerarCronograma").dialog("open");
    });
});