$(function() {
    $.noConflict();

    /* MÓDULO */
    $("#adicionarSetorAcesso").click(function() {
        $(".dialogForm").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 210,
            width: 350,
            modal: true,
            resizable: false,
            title: "ADICIONAR ACESSO AO SETOR",
            buttons: {
                "Cadastrar": function() {
                    $.ajax({
                        url: "adicionarSetor.php",
                        type: "post",
                        data: $("#frmAdicionarSetor").serialize(),
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
                    $(this).dialog("destroy");
                },
                "Cancelar": function() {
                    $(this).dialog("destroy");
                }
            }
        });
        $(".dialogForm").dialog("open");
    });

    $(document).on('click', '.excluirSetorAcesso', function() {
        var setor, idAcessoSetor, nomeSetor;

        setor = $(this).parent().parent()[0];
        idAcessoSetor = setor.cells[1].getElementsByTagName("input")[0].value;
        nomeSetor = setor.cells[0].innerHTML;

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
                        data: "id=" + idAcessoSetor,
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
});

