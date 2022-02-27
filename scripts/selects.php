<?

include_once "../classes/dao/ServicoDAO.php";

$relatorio = filter_input(INPUT_POST, "relatorio");
$select_extra = filter_input(INPUT_POST, "select_extra");
?>

<? if ($relatorio == 1) { ?>
    <select name="selectRel1" id="selectRel1">
        <option value="1">COM BDI</option>
        <option value="2">SEM BDI</option>
        <option value="3">SOMENTE MO</option>
    </select>

<? } else if ($relatorio == 2) { ?>

    <select name="selectRel2" id="selectRel2">
        <option value="1">COM BDI</option>
        <option value="2">SEM BDI</option>
    </select>

<? } else if ($relatorio == 3) { ?>

    <select name="selectRel3" id="selectRel3">
        <option value="1">COM BDI</option>
        <option value="2">SEM BDI</option>
        <option value="3">POR SERVI&Ccedil;O</option>
    </select>

<? } else if ($relatorio == 4) { ?>

    <select name="selectRel4" id="selectRel4">
        <option value="1">F&Iacute;SICO</option>
        <option value="2">FINANCEIRO</option>
        <option value="3">TUDO</option>
    </select>

<? } ?>

<?

if ($select_extra == 3) {
    $servicoDAO = new ServicoDAO();
    echo $servicoDAO->getSelectServico("", "composicao");
}
?>

