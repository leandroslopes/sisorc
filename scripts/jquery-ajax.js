$(function() {

    /* COMPOSICAO */
    $('#descPesquisa').keyup(function() {
        var descPesquisa = $(this).val();

        if (descPesquisa !== "") {
            $.ajax({
                type: "POST",
                url: "../../scripts/ajax.php",
                data: {tipo: "composicao", descPesquisa: descPesquisa},
                dataType: "json",
                success: function(composicoes) {
                    if (composicoes !== "") {
                        var opcoes = "";
                        $.each(composicoes, function() {
                            $.each(this, function(index, valor) {
                                opcoes += "<option value='" + index + "'>" + index + " - " + valor + "</option>";
                            });
                        });
                        $('#compPesquisa').html(opcoes);
                    } else {
                        var opcao = "<option value=''>Nenhuma composi&ccedil;&atilde;o encontrada</option>";
                        $('#compPesquisa').html(opcao);
                    }
                }
            });
        } else {
            var opcao = "<option value=''>Informe o nome da composi&ccedil;&atilde;o</option>";
            $('#compPesquisa').html(opcao);
        }
    });

    $('#compPesquisa').click(function() {
        var codigo = $('#compPesquisa option:selected').val();
        var idMod = $('#idMod').val();
        var url = 'verComp.php?id=' + idMod + '&codigo=' + codigo;
        $(document).redirecionar(url);
    });

    $('#insDescAdd').keyup(function() {
        var insDescAdd = $(this).val(), idComp = $('#idComp').val(), insPesquisa = $('#insPesquisa'), insumo = $('#insumo');

        if (insDescAdd !== "") {
            $.ajax({
                type: "POST",
                url: "../../scripts/ajax.php",
                data: {tipo: "composicao", idComp: idComp, insDescAdd: insDescAdd},
                dataType: 'json',
                success: function(insumos) {
                    if (insumos !== "") {
                        var opcoes = "";
                        $.each(insumos, function() {
                            $.each(this, function(index, valor) {
                                opcoes += "<option value='" + index + "'>" + index + " - " + valor + "</option>";
                            });
                        });
                        insPesquisa.html(opcoes);
                    } else {
                        var opcao = "<option value=''>Nenhum insumo encontrado</option>";
                        insPesquisa.html(opcao);
                        insumo.html("");
                    }
                }
            });
        } else {
            var opcao = "<option value=''>Informe a descrição do insumo</option>";
            insPesquisa.html(opcao);
            insumo.html("");
        }
    });

    $('#insPesquisa').click(function() {
        var insumo = $('#insPesquisa option:selected').text();
        $('#insumo').html(insumo);
    });

    /* INSUMO */
    $('#insDescPesquisa').keyup(function() {
        var insDescPesquisa = $(this).val();

        if (insDescPesquisa !== "") {
            $.ajax({
                type: 'POST',
                url: '../../scripts/ajax.php',
                data: {tipo: 'insumo', insDescPesquisa: insDescPesquisa},
                dataType: 'json',
                success: function(insumos) {
                    if (insumos !== "") {
                        var opcoes = "";
                        $.each(insumos, function() {
                            $.each(this, function(index, valor) {
                                opcoes += "<option value='" + index + "'>" + index + " - " + valor + "</option>";
                            });
                        });
                        $('#insPesquisa').html(opcoes);
                    } else {
                        var opcao = "<option value=''>Nenhum insumo encontrado</option>";
                        $('#insPesquisa').html(opcao);
                    }
                }
            });
        } else {
            var opcao = "<option value=''>Informe o nome do insumo</option>";
            $('#insPesquisa').html(opcao);
        }
    });

    /* ORCAMENTO */
    $('#compDesc').keyup(function() {
        var compDesc = $(this).val(), idOrc = $('#idOrc').val();

        if (compDesc !== "") {
            $.ajax({
                type: "POST",
                url: "../../scripts/ajax.php",
                data: {tipo: 'composicao', orcamento: idOrc, descricao: compDesc},
                dataType: 'json',
                success: function(json) {
                    if (json !== "") {
                        var opcoes = "";
                        $.each(json, function() {
                            $.each(this, function(index, valor) {
                                opcoes += "<option value='" + index + "'>" + index + " - " + valor + "</option>";
                            });
                        });
                        $('#composicoes').html(opcoes);
                    } else {
                        var opcao = "<option value=''>Nenhuma composi&ccedil;&atilde;o encontrada</option>";
                        $('#composicoes').html(opcao);
                    }
                }
            });
        } else {
            var opcao = "<option value=''>Informe o nome da composi&ccedil;&atilde;o</option>";
            $('#composicoes').html(opcao);
        }
    });

    $('#composicoes').click(function() {
        var descricao = $('#composicoes option:selected').text();
        var codigo = $('#composicoes option:selected').val();
        $('#compCod').val(codigo);
        $('#composicao').html(descricao);

        $.ajax({
            type: "POST",
            url: "../../scripts/ajax.php",
            data: {tipo: "composicao", codigo: codigo},
            dataType: 'json',
            success: function(json) {
                if (json !== "") {
                    var opcoes = "";
                    $.each(json, function() {
                        $.each(this, function(index, valor) {
                            opcoes += "<option value='" + index + "'>" + index + " - " + valor + "</option>";
                        });
                    });
                    $('#insumos').html(opcoes);
                } else {
                    var opcao = "<option value=''>Esta composi&ccedil;&atilde;o n&atilde;o possui insumos</option>";
                    $('#insumos').html(opcao);
                }
            }
        });
    });

    $('#insDesc').keyup(function() {
        var idServ = $('#serv_ins').val(), insDesc = $(this).val();

        if (insDesc !== "") {
            $.ajax({
                url: '../../scripts/ajax.php',
                type: 'post',
                data: {tipo: 'insumo', idServ: idServ, insDesc: insDesc},
                dataType: 'json',
                success: function(json) {
                    if (json !== "") {
                        var opcoes = "";
                        $.each(json, function() {
                            $.each(this, function(index, valor) {
                                opcoes += "<option value='" + index + "'>" + index + " - " + valor + "</option>";
                            });
                        });
                        $('#insAdd').html(opcoes);
                    } else {
                        var opcao = "<option value=''>Nenhum insumo encontrado</option>";
                        $('#insAdd').html(opcao);
                    }
                }
            });
        } else {
            var opcao = "<option value=''>Informe o nome do insumo</option>";
            $('#insAdd').html(opcao);
        }
    });

    $('#insAdd').click(function() {
        var insumo = $('#insAdd option:selected').text();
        $('#insumo').html(insumo);
    });

    $(document).on("click", ".ativarComp", function() {
        var id, composicao, ativa, colAtiva, img;
        composicao = $(this).parent().parent()[0];
        ativa = composicao.cells[8].getElementsByTagName("input")[0].value;
        id = composicao.cells[0].getElementsByTagName("input")[0].value;
        colAtiva = composicao.cells[8];

        if (ativa !== '1') {
            ativa = 1;
        } else {
            ativa = 2;
        }

        $.ajax({
            url: 'ativarComp.php',
            type: 'post',
            data: {id: id, ativa: ativa},
            success: function(retorno) {
                var ativou = retorno.trim(), input = "<input type='hidden' name='ativa' id='ativa' value='" + ativou + "'/>";
                if (ativou === '1') {
                    img = "<img src='../../imagens/icones/ativar.png' title='Ativar' alt='' class='ativarComp tam16'/>";
                    img += "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirOrcComp cursor tam16'/>";
                } else {
                    img = "<img src='../../imagens/icones/desativar.png' title='Desativar' alt='' class='ativarComp tam16'/>";
                    img += "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirOrcComp cursor tam16'/>";
                }
                colAtiva.innerHTML = input + img;
            }
        });
    });

    $('#nome_obra').keyup(function() {
        var idOrc = $('#id_orc_cop').val(), nomeObra = $(this).val();

        if (nomeObra !== "") {
            $.ajax({
                url: '../../scripts/ajax.php',
                type: 'post',
                data: {tipo: 'orcamento', idOrc: idOrc, nomeObra: nomeObra},
                dataType: 'json',
                success: function(json) {
                    if (json !== "") {
                        var opcoes = "";
                        $.each(json, function() {
                            $.each(this, function(index, valor) {
                                opcoes += "<option value='" + index + "'>" + index + " - " + valor + "</option>";
                            });
                        });
                        $('#obras').html(opcoes);
                    } else {
                        var opcao = "<option value=''>Nenhum orçamento encontrado</option>";
                        $('#obras').html(opcao);
                    }
                }
            });
        } else {
            var opcao = "<option value=''>Informe o nome da obra</option>";
            $('#obras').html(opcao);
        }
    });

    $('#obras').click(function() {
        var nomeObra = $('#obras option:selected').text();
        var idOrc = $('#obras option:selected').val();
        $('#obra').html(nomeObra);

        $.ajax({
            type: 'post',
            url: '../../scripts/ajax.php',
            data: {tipo: 'orcamento', idOrc: idOrc},
            dataType: 'json',
            success: function(json) {
                if (json !== "") {
                    var opcoes = "";
                    $.each(json, function() {
                        $.each(this, function(index, valor) {
                            opcoes += "<option value='" + index + "'>" + index + " - " + valor + "</option>";
                        });
                    });
                    $('#composicoes_cop').html(opcoes);
                } else {
                    var opcao = "<option value=''>Este or&ccedil;amento n&atilde;o possui composi&ccedil;&otilde;es</option>";
                    $('#composicoes_cop').html(opcao);
                }
            }
        });
    });

    $('#composicoes_cop').click(function() {
        var composicao = $('#composicoes_cop option:selected').text();
        $('#composicao_cop').html(composicao);
    });

    $('#nome_pesq').keyup(function() {
        var idOrc = $('#id_orc_add').val(), nomePesq = $(this).val(), selectTitulos = $('#titulos'), titulo = $('#titulo');

        if (nomePesq !== "") {
            $.ajax({
                type: 'post',
                url: '../../scripts/ajax.php',
                data: {tipo: 'titulo', idOrc: idOrc, nomePesq: nomePesq},
                dataType: 'json',
                success: function(titulos) {
                    if (titulos !== "") {
                        var opcoes = "";
                        $.each(titulos, function() {
                            $.each(this, function(index, valor) {
                                opcoes += "<option value='" + index + "'>" + valor + "</option>";
                            });
                        });
                        selectTitulos.html(opcoes);
                    } else {
                        var opcao = "<option value=''>Nenhum título encontrado</option>";
                        selectTitulos.html(opcao);
                        titulo.html('');
                    }
                }
            });
        } else {
            var opcao = "<option value=''>Informe o nome do título</option>";
            selectTitulos.html(opcao);
            titulo.html('');
        }
    });

    $('#titulos').click(function() {
        var titulo = $('#titulos option:selected').text();
        $('#titulo').html(titulo);
    });

    $('#subtit_pesq').keyup(function() {
        var nomePesq = $(this).val(), selectSubtitulos = $('#subtitulos'), subtitulo = $('#subtitulo');

        if (nomePesq !== "") {
            $.ajax({
                type: 'post',
                url: '../../scripts/ajax.php',
                data: $('#frmAdicionarSubTit').serialize(),
                dataType: 'json',
                success: function(subtitulos) {
                    if (subtitulos !== "") {
                        var opcoes = "";
                        $.each(subtitulos, function() {
                            $.each(this, function(index, valor) {
                                opcoes += "<option value='" + index + "'>" + valor + "</option>";
                            });
                        });
                        selectSubtitulos.html(opcoes);
                    } else {
                        var opcao = "<option value=''>Nenhum subtítulo encontrado</option>";
                        selectSubtitulos.html(opcao);
                        subtitulo.html('');
                    }
                }
            });
        } else {
            var opcao = "<option value=''>Informe o nome do subtítulo</option>";
            selectSubtitulos.html(opcao);
            subtitulo.html('');
        }
    });

    $('#subtitulos').click(function() {
        var subtitulo = $('#subtitulos option:selected').text();
        $('#subtitulo').html(subtitulo);
    });

    /* FUNCIONARIO */
    $("#codigo").keyup(function() {
        var codigo = $(this).val();

        if (codigo !== "") {
            $.ajax({
                type: "post",
                url: "../../scripts/ajax.php",
                data: {tipo: "funcionario", codigo: codigo},
                dataType: "json",
                success: function(funcionarios) {
                    if (funcionarios !== "") {
                        var opcoes = "";
                        $.each(funcionarios, function() {
                            $.each(this, function(index, valor) {
                                opcoes += "<option value='" + index + "'>" + valor + "</option>";
                            });
                        });
                        $("#funcionarios").html(opcoes);
                    } else {
                        var opcao = "<option value=''>Nenhum cadastro encontrado</option>";
                        $("#funcionarios").html(opcao);
                    }
                }
            });
        } else {
            var opcao = "<option value=''>Informe o código do funcionário</option>";
            $("#funcionarios").html(opcao);
        }
    });

    $("#funcionarios").click(function() {
        var funcionario = $("#funcionarios option:selected").text();
        $('#funcionario').html(funcionario);
    });

    /* CRONOGRAMA */
    $(document).on("keyup", ".porcentagem", function() {
        var titulo, quantidade, indiceTD, porcentagem, auxPorcentagem, somaPorcentagem, valorMes, total, totalPercentual;

        titulo = $(this).parent().parent()[0];
        indiceTD = $(this).parent().index();
        quantidade = parseInt($("#qtd").val());

        porcentagem = titulo.cells[indiceTD].getElementsByTagName("input")[0].value;
        valorMes = titulo.cells[indiceTD].getElementsByTagName("label")[0];

        total = titulo.cells[2].getElementsByTagName("input")[0].value;
        totalPercentual = titulo.cells[3 + quantidade];

        somaPorcentagem = 0.0;
        for (i = 3; i < (quantidade + 3); i++) {
            auxPorcentagem = parseInt(titulo.cells[i].getElementsByTagName("input")[0].value);
            if (isNaN(auxPorcentagem)) {
                auxPorcentagem = 0.0;
            }
            somaPorcentagem += auxPorcentagem;
        }
        totalPercentual.innerHTML = somaPorcentagem;

        $.ajax({
            type: "post",
            url: "../../scripts/ajax.php",
            data: {tipo: "cronograma", porcentagem: porcentagem, total: total},
            dataType: "json",
            success: function(valor) {
                valorMes.innerHTML = valor;
            }
        });
    });

    /* IMPRESSAO */
    $("#relatorio").change(function() {
        var arrayRelatorio, selectExtra;

        arrayRelatorio = $(this).val().split('_');
        selectExtra = $("#selectExtra");

        $.ajax({
            type: "post",
            url: "../../scripts/selects.php",
            data: {relatorio: arrayRelatorio[0]},
            success: function(select) {
                selectExtra.html(select);
            }
        });
    });

    $(document).on("change", "#selectRel3", function() {
        var valor, selectExtra;
        
        valor = $(this).val();
        selectExtra = $("#selectExtra2");

        if (valor === "3") {
            $.ajax({
                type: "post",
                url: "../../scripts/selects.php",
                data: {select_extra: valor},
                success: function(select) {
                    selectExtra.html(select);
                }
            });
        }
    });
});