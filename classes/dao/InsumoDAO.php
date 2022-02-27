<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/StringUtil.php";

class InsumoDAO {

    private $conexao;
    private $funcionario;

    public function InsumoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Lista os insumos.
     * @return string
     */
    public function listarInsumos() {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela_insumo' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>C&Oacute;DIGO</th>";
        $conteudo_html_topo .= "<th>NOME</th>";
        $conteudo_html_topo .= "<th>UNIDADE</th>";
        $conteudo_html_topo .= "<th>SERVI&Ccedil;O</th>";
        $conteudo_html_topo .= "<th>TIPO</th>";
        $conteudo_html_topo .= "<th>EDITAR</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.codigo, a.descricao, b.id AS idUni, b.nome AS unidade, c.id AS idServ, c.nome AS servico, d.id AS idTipo, d.nome AS tipo "
                . "FROM insumo AS a, unidade AS b, servico AS c, tipo AS d "
                . "WHERE a.id_uni = b.id AND a.id_serv = c.id AND a.id_tipo = d.id ORDER BY c.nome ASC, a.descricao ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        while ($insumo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td><input type='hidden' name='idIns' id='idIns' value='" . $insumo["codigo"] . "'/>" . $insumo["codigo"] . "</td>";
            $conteudo_html .= "<td>" . $insumo["descricao"] . "</td>";
            $conteudo_html .= "<td><input type='hidden' name='idUni' id='idUni' value='" . $insumo["idUni"] . "'/>" . $insumo["unidade"] . "</td>";
            $conteudo_html .= "<td><input type='hidden' name='idServ' id='idServ' value='" . $insumo["idServ"] . "'/>" . $insumo["servico"] . "</td>";
            $conteudo_html .= "<td><input type='hidden' name='idTipo' id='idTipo' value='" . $insumo["idTipo"] . "'/>" . $insumo["tipo"] . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<img src='../../imagens/icones/editar.png' title='Editar' alt='' class='editarIns tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "</tr>";
        }
        $conteudo_html .= "</tbody>";

        $conteudo_html_rodape .= "<tfoot> <tr> <th colspan='6'>";
        $conteudo_html_rodape .= "<div id='paginacao_insumo' class='paginacao'> <form action=''>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/first.png' alt='' class='first'/>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/prev.png' alt='' class='prev'/>";
        $conteudo_html_rodape .= "<input type='text' class='pagedisplay' size='5'/>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/next.png' alt='' class='next'/>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/last.png' alt='' class='last'/>";
        $conteudo_html_rodape .= "<select class='pagesize'>";
        $conteudo_html_rodape .= "<option selected='selected' value='10'>10</option>";
        $conteudo_html_rodape .= "<option value='20'>20</option>";
        $conteudo_html_rodape .= "</select> </form> </div> </th> </tr> </tfoot> </table>";

        $conteudo_vazio = "<tbody><tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr></tbody>";

        if ($qtdRegistros > 0) {
            return $conteudo_html_topo . $conteudo_html . $conteudo_html_rodape;
        } else {
            return $conteudo_html_topo . $conteudo_vazio . $conteudo_html_rodape;
        }
    }

    /**
     * Verifica se determinado insumo se encontra cadastrado.
     * @param array $insumo
     * @return boolean
     */
    public function isCadastrado($insumo) {
        $query = "SELECT * FROM insumo AS a WHERE a.descricao = '" . $insumo["desc_ins"] . "' "
                . "AND a.id_uni = " . $insumo["uni_ins"] . " AND a.id_serv = " . $insumo["serv_ins"] . " AND a.id_tipo = " . $insumo["tipo_ins"];
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Cadastra insumo.
     * @param array $insumo
     * @return boolean
     */
    public function cadastrar($insumo) {
        $query = "INSERT INTO insumo (descricao, id_uni, id_serv, id_tipo) "
                . "VALUES ('" . $this->conexao->antiInject($insumo["desc_ins"]) . "', "
                . $insumo["uni_ins"] . ", "
                . $insumo["serv_ins"] . ", "
                . $insumo["tipo_ins"] . ")";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Edita insumo.
     * @param array $insumo
     * @return boolean
     */
    public function editar($insumo) {
        $query = "UPDATE insumo "
                . "SET descricao = '" . $this->conexao->antiInject($insumo["desc_ins"]) . "', "
                . "id_uni = " . $insumo["uni_ins"] . ", "
                . "id_serv = " . $insumo["serv_ins"] . ", "
                . "id_tipo = " . $insumo["tipo_ins"] . " "
                . "WHERE codigo = " . $insumo["codigo"];
        return $this->conexao->executaQuery($query);
    }

    /**
     * Retorna os insumos para ve-los.
     * @param string $descricao
     * @return array
     */
    public function pesquisarInsumos($descricao, $id_serv = "") {
        $insumos = "";
        $filtro = "";

        if (!empty($id_serv)) {
            $filtro = "AND a.id_serv = " . $id_serv;
        }

        $query = "SELECT a.codigo, a.descricao FROM insumo AS a WHERE a.descricao LIKE '$descricao%' $filtro ORDER BY a.descricao ASC"; //echo $query;
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);

        $x = 0;
        while ($insumo = $this->conexao->getRegistros($resultado)) {
            $x = $x + 1;
            if ($x == 1) {
                $insumos = array(array($insumo["codigo"] => $insumo["descricao"]));
            } else {
                $insumos[] = array($insumo["codigo"] => $insumo["descricao"]);
            }
        }

        if ($qtdRegistros > 0) {
            return $insumos;
        } else {
            return "";
        }
    }

}
?>

