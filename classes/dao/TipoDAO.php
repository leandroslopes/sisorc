<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";

class TipoDAO {

    private $conexao;
    private $funcionario;

    public function TipoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Lista os tipos.
     * @return html
     */
    public function listarTipos() {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>ID</th>";
        $conteudo_html_topo .= "<th>TIPO</th>";
        $conteudo_html_topo .= "<th>EDITAR</th>";
        $conteudo_html_topo .= "<th>EXCLUIR</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.id, a.nome FROM tipo AS a ORDER BY a.nome";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        while ($tipo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . $tipo["id"] . "</td>";
            $conteudo_html .= "<td>" . $tipo["nome"] . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<input type='hidden' name='idTipo' id='idTipo' value='" . $tipo["id"] . "'/>";
            $conteudo_html .= "<img src='../../imagens/icones/editar.png' title='Editar' alt='' class='editarTipo tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirTipo tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "</tr>";
        }
        $conteudo_html .= "</tbody>";

        $conteudo_html_rodape .= "<tfoot> <tr> <th colspan='4'>";
        $conteudo_html_rodape .= "<div id='paginacao' class='paginacao'> <form action=''>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/first.png' alt='' class='first'/>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/prev.png' alt='' class='prev'/>";
        $conteudo_html_rodape .= "<input type='text' class='pagedisplay' size='5'/>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/next.png' alt='' class='next'/>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/last.png' alt='' class='last'/>";
        $conteudo_html_rodape .= "<select class='pagesize'>";
        $conteudo_html_rodape .= "<option selected='selected' value='10'>10</option>";
        $conteudo_html_rodape .= "<option value='20'>20</option>";
        $conteudo_html_rodape .= "</select> </form> </div> </th> </tr> </tfoot> </table>";

        $conteudo_vazio = "<tbody><tr><td>-</td><td>-</td><td>-</td><td>-</td></tr></tbody>";

        if ($qtdRegistros > 0) {
            return $conteudo_html_topo . $conteudo_html . $conteudo_html_rodape;
        } else {
            return $conteudo_html_topo . $conteudo_vazio . $conteudo_html_rodape;
        }
    }

    /**
     * Cadastra tipo.
     * @param string $tipo
     * @return boolean
     */
    public function cadastrar($tipo) {
        $tipo = $this->conexao->antiInject($tipo);
        $query = "INSERT INTO tipo (nome) VALUES ('$tipo')"; //echo $query;
        return $this->conexao->executaQuery($query);
    }

    /**
     * Edita o tipo.
     * @param inteiro $id
     * @param string $tipo
     * @return boolean
     */
    public function editar($id, $tipo) {
        $tipo = $this->conexao->antiInject($tipo);
        $query = "UPDATE tipo SET nome = '$tipo' WHERE id = $id";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Exclui o tipo.
     * @param string $tipo
     * @return boolean
     */
    public function excluir($tipo) {
        $query = "DELETE FROM tipo WHERE nome = '$tipo'";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Verifica se determinado tipo se encontra cadastrado.
     * @param string $tipo
     * @return boolean
     */
    public function isCadastrado($tipo) {
        $tipo = $this->conexao->antiInject($tipo);
        $query = "SELECT * FROM tipo AS a WHERE a.nome = '$tipo'";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Verifica se o tipo está relacionado com alguma composicao ou insumo.
     * @param inteiro $id_tipo
     * @return boolean
     */
    public function estaRelacionado($id_tipo) {
        $queryComp = "SELECT * FROM composicao AS a WHERE a.id_tipo = '$id_tipo'";
        $resultComp = $this->conexao->executaQuery($queryComp);
        $qtdComp = $this->conexao->getQtdRegistros($resultComp);

        $queryIns = "SELECT * FROM insumo AS a WHERE a.id_tipo = '$id_tipo'";
        $resultIns = $this->conexao->executaQuery($queryIns);
        $qtdIns = $this->conexao->getQtdRegistros($resultIns);

        $qtdTotal = $qtdComp + $qtdIns;

        if ($qtdTotal > 0) {
            return true;
        }
        return false;
    }

    /**
     * Retorna um 'select' com os tipos.
     * @param inteiro $id_tipo
     * @return string
     */
    public function getSelectTipo($id_tipo, $q) {

        $q == "composicao" ? $q = "tipo" : $q = "tipo_ins";

        $query = "SELECT a.* FROM tipo AS a ORDER BY a.nome";
        $resultado = $this->conexao->executaQuery($query);

        $conteudo_html = "";
        $conteudo_html .= "<select name='$q' id='$q'>";
        while ($tipo = $this->conexao->getRegistros($resultado)) {
            if ($tipo["id"] == $id_tipo) {
                $conteudo_html .= "<option value='" . $tipo["id"] . "' selected>" . $tipo["nome"] . "</option>";
            } else {
                $conteudo_html .= "<option value='" . $tipo["id"] . "'>" . $tipo["nome"] . "</option>";
            }
        }
        $conteudo_html .= "</select>";

        return $conteudo_html;
    }

}

?>
