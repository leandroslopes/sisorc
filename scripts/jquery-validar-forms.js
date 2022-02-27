$(function() {
    $("#formLogin").validate({
        rules: {
            codigo: {
                required: true,
                number: true
            },
            senha: {
                required: true
            }
        },
        messages: {
            codigo: {
                required: "Informe o código",
                number: "Informe somente número"
            },
            senha: {
                required: "Informe a senha"
            }
        }
    });

    $("#frmAlterarSenha").validate({
        rules: {
            senha: {
                required: true
            },
            confSenha: {
                required: true,
                equalTo: "#senha"
            }
        },
        messages: {
            senha: {
                required: "Informe a senha"
            },
            confSenha: {
                required: "Informe a confirmação de senha",
                equalTo: "Repita a senha informada"
            }
        }
    });

    $("#frmCadastrar").validate({
        rules: {
            nome: {
                required: true
            }
        },
        messages: {
            nome: {
                required: "Informe o nome"
            }
        }
    });

    $("#frmCadOrc").validate({
        rules: {
            nome_obra: {
                required: true
            },
            nome_cliente: {
                required: true
            },
            local: {
                required: true
            },
            area: {
                required: true
            },
            bdi: {
                required: true
            },
            encargo_social: {
                required: true
            }
        },
        messages: {
            nome_obra: {
                required: "Informe o nome da obra"
            },
            nome_cliente: {
                required: "Informe o nome do cliente"
            },
            local: {
                required: "Informe o local"
            },
            area: {
                required: "Informe o tamanho da área"
            },
            bdi: {
                required: "Informe o B.D.I."
            },
            encargo_social: {
                required: "Informe o encargo social"
            }
        }
    });

    $("#frmImprimir").validate({
        rules: {
            relatorio: {
                required: true
            }
        },
        messages: {
            relatorio: {
                required: "Selecione o relatório"
            }
        }
    });
});