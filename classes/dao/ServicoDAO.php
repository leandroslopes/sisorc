<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";

class ServicoDAO {

    private $conexao;
    private $funcionario;

    public function ServicoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Lista os servicos.
     * @return html
     */
    public function listarServicos() {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela_servico' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>ID</th>";
        $conteudo_html_topo .= "<th>SERVI&Ccedil;OS</th>";
        $conteudo_html_topo .= "<th>EDITAR</th>";
        $conteudo_html_topo .= "<th>EXCLUIR</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.id, a.nome FROM servico AS a ORDER BY a.nome ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        while ($servico = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . $servico["id"] . "</td>";
            $conteudo_html .= "<td>" . $servico["nome"] . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<input type='hidden' name='idServ' id='idServ' value='" . $servico["id"] . "'/>";
            $conteudo_html .= "<img src='../../imagens/icones/editar.png' title='Editar' alt='' class='editarServico tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirServico tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "</tr>";
        }
        $conteudo_html .= "</tbody>";

        $conteudo_html_rodape .= "<tfoot> <tr> <th colspan='4'>";
        $conteudo_html_rodape .= "<div id='paginacao_servico' class='paginacao'> <form action=''>";
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
     * Cadastra servico.
     * @param string $servico
     * @return boolean
     */
    public function cadastrar($servico) {
        $servico = $this->conexao->antiInject($servico);
        $query = "INSERT INTO servico (nome) VALUES ('$servico')";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Edita servico.
     * @param inteiro $id
     * @param string $servico
     * @return boolean
     */
    public function editar($id, $servico) {
        $servico = $this->conexao->antiInject($servico);
        $query = "UPDATE servico SET nome = '$servico' WHERE id = $id";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Exclui servico.
     * @param string $servico
     * @return boolean
     */
    public function excluir($servico) {
        $query = "DELETE FROM servico WHERE nome = '$servico'";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Verifica se determinado servico se encontra cadastrado.
     * @param string $servico
     * @return boolean
     */
    public function isCadastrado($servico) {
        $servico = $this->conexao->antiInject($servico);
        $query = "SELECT * FROM servico AS a WHERE a.nome = '$servico'";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Verifica se o servico está relacionado com alguma composicao ou insumo.
     * @param inteiro $id_serv
     * @return boolean
     */
    public function estaRelacionado($id_serv) {
        $queryComp = "SELECT * FROM composicao AS a WHERE a.id_serv = '$id_serv'";
        $resultComp = $this->conexao->executaQuery($queryComp);
        $qtdComp = $this->conexao->getQtdRegistros($resultComp);

        $queryIns = "SELECT * FROM insumo AS a WHERE a.id_serv = '$id_serv'";
        $resultIns = $this->conexao->executaQuery($queryIns);
        $qtdIns = $this->conexao->getQtdRegistros($resultIns);

        $qtdTotal = $qtdComp + $qtdIns;

        if ($qtdTotal > 0) {
            return true;
        }
        return false;
    }

    /**
     * Retorna um 'select' com os servicos.
     * @param inteiro $id_servico
     * @param string $q
     * @return string
     */
    public function getSelectServico($id_servico, $q) {
        $filtro = "";

        if ($q == "composicao") { 
            $q = "servico";
        } else {
            $q = "serv_ins";
            $filtro = "WHERE a.nome != 'CO'";
        }
        
        $query = "SELECT a.* FROM servico AS a $filtro ORDER BY a.nome";
        $resultado = $this->conexao->executaQuery($query);

        $conteudo_html = "";
        $conteudo_html .= "<select name='$q' id='$q'>";
        while ($servico = $this->conexao->getRegistros($resultado)) {
            if ($servico["id"] == $id_servico) {
                $conteudo_html .= "<option value='" . $servico["id"] . "' selected>" . $servico["nome"] . "</option>";
            } else {
                $conteudo_html .= "<option value='" . $servico["id"] . "'>" . $servico["nome"] . "</option>";
            }
        }
        $conteudo_html .= "</select>";

        return $conteudo_html;
    }

}

?>