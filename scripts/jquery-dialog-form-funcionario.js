$(function() {
    $.noConflict();

    $("#adicionarFuncionario").click(function() {
        var codigo, funcionarios, funcionario, cargo, setor, situacao;

        codigo = $("#codigo");
        funcionarios = $("#funcionarios");
        funcionario = $("#funcionario");
        cargo = $("#cargo");
        setor = $("#setor");
        situacao = $("#situcao");

        $(".dialogForm").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 440,
            width: 625,
            modal: true,
            resizable: false,
            title: "ADICIONAR FUNCIONÁRIO",
            buttons: {
                Cadastrar: function() {
                    if (funcionario.html() !== "") {
                        $.ajax({
                            url: "adicionarFuncionario.php",
                            type: "post",
                            data: $("#frmAdicionarFuncionario").serialize(),
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
                        codigo.val("");
                        funcionarios.html("");
                        funcionario.html("");
                        $(this).dialog("destroy");
                    } else {
                        codigo.val("");
                        funcionarios.html("");
                        funcionario.html("");
                        $(this).dialog("destroy");
                        var mensagem = $("#msgErroDialog");
                        updateMessage(mensagem);
                    }
                },
                Cancelar: function() {
                    codigo.val("");
                    funcionarios.html("");
                    funcionario.html("");
                    $(this).dialog("destroy");
                }
            }
        });
        $(".dialogForm").dialog("open");
    });

    $(document).on('click', '.editarFuncionario', function() {
        var funcionario, codigo, codigoEdd, nome, nomeFuncionario, idCargo, selectCargo, idSetor, selectSetor, idSituacao, selectSituacao;

        funcionario = $(this).parent().parent()[0];
        nome = funcionario.cells[0].getElementsByTagName("label")[0].innerHTML;
        codigo = funcionario.cells[0].getElementsByTagName("input")[0].value;
        idCargo = funcionario.cells[1].getElementsByTagName("input")[0].value;
        idSetor = funcionario.cells[2].getElementsByTagName("input")[0].value;
        idSituacao = funcionario.cells[3].getElementsByTagName("input")[0].value;
        
        codigoEdd = $("#codigo_edd");
        nomeFuncionario = $("#nome_funcionario");
        selectCargo = $("#cargo_edd");
        selectSetor = $("#setor_edd");
        selectSituacao = $("#situacao_edd");
        
        codigoEdd.val(codigo)
        nomeFuncionario.html(nome);
        selectCargo.val(idCargo);
        selectSetor.val(idSetor);
        selectSituacao.val(idSituacao);
        
        $(".dialogFormEditar").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 300,
            width: 410,
            modal: true,
            title: "EDITAR",
            buttons: {
                Editar: function() {
                    $.ajax({
                        url: "editarFuncionario.php",
                        type: "post",
                        data: $("#frmEditarFuncionario").serialize(),
                        success: function(retorno) {
                            var mensagem, editou = retorno.trim();
                            if (editou === "TRUE") {
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
});