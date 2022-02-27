<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";

class SetorDAO {

    private $conexao;
    private $funcionario;

    public function SetorDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Lista os setores.
     * @return html
     */
    public function listarSetores() {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>SETOR</th>";
        $conteudo_html_topo .= "<th>DATA DE CADASTRO</th>";
        $conteudo_html_topo .= "<th>EDITAR</th>";
        $conteudo_html_topo .= "<th>EXCLUIR</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.id, a.nome, a.usuario_cadastro, DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS dtCadastro FROM setor AS a ORDER BY a.nome";
        $resultado = $this->conexao->executaQuery($query);
        while ($setor = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . utf8_encode($setor["nome"]) . "</td>";
            $conteudo_html .= "<td>" . $setor["dtCadastro"] . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<input type='hidden' name='idSetor' id='idSetor' value='" . $setor["id"] . "'/>";
            $conteudo_html .= "<img src='../../imagens/icones/editar.png' title='Editar' alt='' class='editarSetor tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirSetor tam16' />";
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

        return $conteudo_html_topo . $conteudo_html . $conteudo_html_rodape;
    }

    /**
     * Cadastra setor.
     * @param string $setor
     * @return boolean
     */
    public function cadastrar($setor) {
        $setor = $this->conexao->antiInject($setor);
        $query = "INSERT INTO setor (nome, usuario_cadastro, data_cadastro) VALUES ('$setor', " . $this->funcionario->getCodigo() . ", NOW())";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Edita setor.
     * @param inteiro $id
     * @param string $setor
     * @return boolean
     */
    public function editar($id, $setor) {
        $setor = $this->conexao->antiInject($setor);
        $query = "UPDATE setor SET nome = '$setor', usuario_cadastro = " . $this->funcionario->getCodigo() . ", data_cadastro = NOW() WHERE id = $id";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Exclui setor.
     * @param string $setor
     * @return boolean
     */
    public function excluirSetor($setor) {
        $query = "DELETE FROM setor WHERE nome = '$setor'"; //echo $query;
        return $this->conexao->executaQuery($query);
    }

    /**
     * Verifica se determinado setor se encontra cadastrado.
     * @param string $setor
     * @return boolean
     */
    public function isCadastrado($setor) {
        $setor = $this->conexao->antiInject($setor);
        $query = "SELECT * FROM setor AS a WHERE a.nome = '$setor'";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0)
            return true;
        return false;
    }

    /**
     * Verifica se o setor está relacionado ao módulo.
     * @param inteiro $id_setor
     * @return boolean
     */
    public function estaModulo($id_setor) {
        $query = "SELECT * FROM mod_acesso AS a WHERE a.id_setor = $id_setor";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    public function getSelect($operacao = "") {
        $conteudo_html = "";
        $select_id = "";

        $operacao == "add" ? $select_id = "setor" : $select_id = "setor_edd";

        $query = "SELECT * FROM setor AS a ORDER BY a.nome ASC";
        $resultado = $this->conexao->executaQuery($query);

        $conteudo_html .= "<select name='setor' id='$select_id'>";
        while ($setor = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<option value='" . $setor["id"] . "'>" . $setor["nome"] . "</option>";
        }
        $conteudo_html .= "</select>";

        return $conteudo_html;
    }

}

?>
