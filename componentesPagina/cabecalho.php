<?
include_once '../classes/util/SistemaUtil.php';
include_once '../classes/dao/ModuloDAO.php';

$moduloDAO = new ModuloDAO();
$id_modulo = $_REQUEST["id_modulo"];

if ($id_modulo == "login") {
    $pagina = "LOGIN";
} else if ($id_modulo == "inicio") {
    $pagina = "INÍCIO";
} else {
    $modulo = $moduloDAO->getModulo($id_modulo);
    $pagina = utf8_encode($modulo["nome"]);
}
?>
<div class="cabecalho">
    <div class="tituloCabecalho">  
        <h1><?= SistemaUtil::SISTEMA_TITULO . " | " . $pagina ?></h1>
        <label>Sistema de Gerenciamento APOTEX v.01</label>
    </div>
</div>
<div class="linha"></div>