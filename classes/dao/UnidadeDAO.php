<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";

class UnidadeDAO {

    private $conexao;
    private $funcionario;

    public function UnidadeDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();
        $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
    }

    /**
     * Lista as unidades.
     * @return html
     */
    public function listarUnidades() {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela_unidade' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>ID</th>";
        $conteudo_html_topo .= "<th>UNIDADE</th>";
        $conteudo_html_topo .= "<th>EDITAR</th>";
        $conteudo_html_topo .= "<th>EXCLUIR</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.id, a.nome FROM unidade AS a ORDER BY a.nome";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        while ($unidade = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . $unidade["id"] . "</td>";
            $conteudo_html .= "<td>" . $unidade["nome"] . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<input type='hidden' name='idUnidade' id='idUnidade' value='" . $unidade["id"] . "'/>";
            $conteudo_html .= "<img src='../../imagens/icones/editar.png' title='Editar' alt='' class='editarUnidade tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirUnidade tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "</tr>";
        }
        $conteudo_html .= "</tbody>";

        $conteudo_html_rodape .= "<tfoot> <tr> <th colspan='4'>";
        $conteudo_html_rodape .= "<div id='paginacao_unidade' class='paginacao'> <form action=''>";
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

        if ($qtdRegistros > 0)
            return $conteudo_html_topo . $conteudo_html . $conteudo_html_rodape;
        else
            return $conteudo_html_topo . $conteudo_vazio . $conteudo_html_rodape;
    }

    /**
     * Cadastra unidade.
     * @param string $unidade
     * @return boolean
     */
    public function cadastrar($unidade) {
        $unidade = $this->conexao->antiInject($unidade);
        $query = "INSERT INTO unidade (nome) VALUES ('$unidade')";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Edita unidade.
     * @param inteiro $id
     * @param string $unidade
     * @return boolean
     */
    public function editar($id, $unidade) {
        $unidade = $this->conexao->antiInject($unidade);
        $query = "UPDATE unidade SET nome = '$unidade' WHERE id = $id";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Exclui unidade.
     * @param string $unidade
     * @return boolean
     */
    public function excluir($unidade) {
        $query = "DELETE FROM unidade WHERE nome = '$unidade'";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Verifica se determinada unidade se encontra cadastrada.
     * @param string $unidade
     * @return boolean
     */
    public function isCadastrado($unidade) {
        $unidade = $this->conexao->antiInject($unidade);
        $query = "SELECT * FROM unidade AS a WHERE a.nome = '$unidade'";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0)
            return true;
        return false;
    }

    /**
     * Retorna um 'select' com as unidades.
     * @param inteiro $id_unidade
     * @return string
     */
    public function getSelectUnidade($id_unidade, $q) {

        $q == "composicao" ? $q = "unidade" : $q = "uni_ins";

        $query = "SELECT a.* FROM unidade AS a ORDER BY a.nome";
        $resultado = $this->conexao->executaQuery($query);

        $conteudo_html = "";
        $conteudo_html .= "<select name='$q' id='$q'>";
        while ($unidade = $this->conexao->getRegistros($resultado)) {
            if ($unidade["id"] == $id_unidade) {
                $conteudo_html .= "<option value='" . $unidade["id"] . "' selected>" . $unidade["nome"] . "</option>";
            } else {
                $conteudo_html .= "<option value='" . $unidade["id"] . "'>" . $unidade["nome"] . "</option>";
            }
        }
        $conteudo_html .= "</select>";

        return $conteudo_html;
    }

}

?>
