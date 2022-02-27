$(function() {
    $.noConflict();
    
    /* SUBMÓDULO */
    $("#adicionarCargoAcesso").click(function() {
        $(".dialogForm").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 210,
            width: 350,
            modal: true,
            resizable: false,
            title: "ADICIONAR ACESSO AO CARGO",
            buttons: {
                "Cadastrar": function() {
                    $.ajax({
                        url: "adicionarCargo.php",
                        type: "post",
                        data: $("#frmAdicionarCargo").serialize(),
                        success: function(retorno) {
                            var mensagem, cadastrou = retorno.trim();
                            if (cadastrou == "TRUE") {
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

    $(document).on('click', '.excluirCargoAcesso', function() {
        var cargo, idAcessoCargo, nomeCargo;

        cargo = $(this).parent().parent()[0];
        idAcessoCargo = cargo.cells[1].getElementsByTagName("input")[0].value;
        nomeCargo = cargo.cells[0].innerHTML;

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
                        data: "id=" + idAcessoCargo,
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
