<?

include_once "../classes/dao/OrcamentoDAO.php";
include_once "../classes/dao/ComposicaoDAO.php";
include_once "../classes/dao/InsumoDAO.php";
include_once "../classes/dao/OrcComposicaoDAO.php";
include_once "../classes/dao/OrcComposicaoSubcomposicaoDAO.php";
include_once "../classes/dao/OrcComposicaoInsumoDAO.php";
include_once "../classes/dao/TituloDAO.php";
include_once "../classes/dao/SubtituloDAO.php";
include_once "../classes/dao/FuncionarioDAO.php";
include_once "../classes/modelo/CronogramaTitulo.php";
include_once "../classes/util/NumeroUtil.php";

$tipo = filter_input(INPUT_POST, "tipo");

if ($tipo == "orcamento") {
    $orcamentoDAO = new OrcamentoDAO();
    $composicaoDAO = new ComposicaoDAO();
    
    $id_orc = filter_input(INPUT_POST, "idOrc");
    $nome_obra = filter_input(INPUT_POST, "nomeObra");
    
    if (!empty($nome_obra)) {
        echo json_encode($orcamentoDAO->pesquisarOrcamentos($id_orc, $nome_obra));
    } else if (!empty($id_orc)) {
        echo json_encode($composicaoDAO->getComposicoesCop($id_orc));
    }
} else if ($tipo == "composicao") {
    $composicaoDAO = new ComposicaoDAO();
    $orcCompInsDAO = new OrcComposicaoInsumoDAO();
    
    $desc_pesquisa = filter_input(INPUT_POST, "descPesquisa");
    $ins_desc_add = filter_input(INPUT_POST, "insDescAdd");    
    $id_comp = filter_input(INPUT_POST, "idComp");    
    $descricao = filter_input(INPUT_POST, "descricao");    
    $orcamento = filter_input(INPUT_POST, "orcamento");    
    $codigo = filter_input(INPUT_POST, "codigo");    
    
    if (!empty($desc_pesquisa)) {
        echo json_encode($composicaoDAO->pesquisarComposicoes($desc_pesquisa));
    } else if (!empty($ins_desc_add)) {
        echo json_encode($composicaoDAO->pesquisarInsumosAdd($id_comp, $ins_desc_add));
    } else if (!empty($descricao)) {
        echo json_encode($composicaoDAO->getComposicoesAdd($orcamento, $descricao));
    } else if (!empty($codigo)) {
        echo json_encode($orcCompInsDAO->getCompInsumos($codigo));
    }
} else if ($tipo == "insumo") {
    $insumoDAO = new InsumoDAO();
    
    $ins_desc_pesquisa = filter_input(INPUT_POST, "insDescPesquisa");
    $ins_desc = filter_input(INPUT_POST, "insDesc");
    $id_serv = filter_input(INPUT_POST, "idServ");
    
    if (!empty($ins_desc_pesquisa)) {
        echo json_encode($insumoDAO->pesquisarInsumos($ins_desc_pesquisa));
    } else if ($ins_desc) {
        echo json_encode($insumoDAO->pesquisarInsumos($ins_desc, $id_serv));
    }
} else if ($tipo == "subcomposicao") {
    $orcCompSubcompDAO = new OrcComposicaoSubcomposicaoDAO();
    
    $id_orc = filter_input(INPUT_POST, "idOrc");
    $id_orc_comp = filter_input(INPUT_POST, "idOrcComp");
    
    echo json_encode($orcCompSubcompDAO->getSubcomposicoesAdd($id_orc, $id_orc_comp));
} else if ($tipo == "titulo") {
    $orcTituloDAO = new TituloDAO();
    
    $id_orc = filter_input(INPUT_POST, "idOrc");
    $nome_pesq = filter_input(INPUT_POST, "nomePesq");
    
    echo json_encode($orcTituloDAO->pesquisarTitulosAdd($id_orc, $nome_pesq));
} else if ($tipo == "subtitulo") {
    $orcSubtituloDAO = new SubtituloDAO();
    
    $orc_titulos = filter_input(INPUT_POST, "orc_titulos");
    $subtit_pesq = filter_input(INPUT_POST, "subtit_pesq");
    
    echo json_encode($orcSubtituloDAO->pesquisarSubtitulosAdd($orc_titulos, $subtit_pesq));
} else if ($tipo == "funcionario") {
    $funcionarioDAO = new FuncionarioDAO();
    
    $codigo = filter_input(INPUT_POST, "codigo");
    
    echo json_encode($funcionarioDAO->pesquisarPessoasAdd($codigo));
} else if ($tipo == "cronograma") {
    $cronogramaTitulo = new CronogramaTitulo();
    $porcentagem = filter_input(INPUT_POST, "porcentagem");
    $total = filter_input(INPUT_POST, "total");
    
    $valorMes = NumeroUtil::formatar($cronogramaTitulo->getValorMes($porcentagem, $total), NumeroUtil::NUMERO_BRA);
    echo json_encode($valorMes);
}
?>