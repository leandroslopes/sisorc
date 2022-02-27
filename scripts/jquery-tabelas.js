$(function() {
    $("#tabela").tablesorter({
        widgets: ['zebra'],
        headers: {
            0: {sorter: false},
            1: {sorter: false},
            2: {sorter: false},
            3: {sorter: false},
            4: {sorter: false},
            5: {sorter: false}
        }
    });
    $("#tabela").tablesorterPager({
        container: $("#paginacao")
    });
    
    $("#tabela_unidade").tablesorter({
        widgets: ['zebra'],
        headers: {
            0: {sorter: false},
            1: {sorter: false},
            2: {sorter: false},
            3: {sorter: false}
        }
    });
    $("#tabela_unidade").tablesorterPager({
        container: $("#paginacao_unidade")
    });
    
    $("#tabela_servico").tablesorter({
        widgets: ['zebra'],
        headers: {
            0: {sorter: false},
            1: {sorter: false},
            2: {sorter: false},
            3: {sorter: false}
        }
    });
    $("#tabela_servico").tablesorterPager({
        container: $("#paginacao_servico")
    });
    
    $("#tabela_composicao").tablesorter({
        widgets: ['zebra'],
        headers: {
            0: {sorter: false},
            1: {sorter: false},
            2: {sorter: false},
            3: {sorter: false},
            4: {sorter: false},
            5: {sorter: false}
        }
    });
    $("#tabela_composicao").tablesorterPager({
        container: $("#paginacao_composicao")
    });
    
    $("#tabela_insumo").tablesorter({
        widgets: ['zebra'],
        headers: {
            0: {sorter: false},
            1: {sorter: false},
            2: {sorter: false},
            3: {sorter: false},
            4: {sorter: false},
            5: {sorter: false}
        }
    });
    $("#tabela_insumo").tablesorterPager({
        container: $("#paginacao_insumo")
    });
});