<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/TituloDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/NumeroUtil.php";

class SubtituloDAO {

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function SubtituloDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Adicionar um subtitulo para um titulo.
     * @param OrcSubtitulo $subtitulo
     * @return boolean
     */
    public function adicionar($subtitulo) {
        $query = "INSERT INTO titulo_subtitulo (id_orc_tit, subtitulo, num_item) "
                . "VALUES (" . $subtitulo->getTitulo()->getId() . ", '" . $this->conexao->antiInject($subtitulo->getSubtitulo()) . "', "
                . "'" . $this->conexao->antiInject($subtitulo->getNumeroItem()) . "')";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Edita um subtitulo.
     * @param OrcSubtitulo $subtitulo
     * @return boolean
     */
    public function editar($subtitulo) {
        $query = "UPDATE titulo_subtitulo SET subtitulo = '" . $this->conexao->antiInject($subtitulo->getSubtitulo()) . "', "
                . "num_item = '" . $this->conexao->antiInject($subtitulo->getNumeroItem()) . "' "
                . "WHERE id = " . $subtitulo->getId();
        return $this->conexao->executaQuery($query);
    }

    /**
     * Exclui subtitulo.
     * @param inteiro $id - ID do subtitulo.
     * @return boolean
     */
    public function excluir($id) {
        $query = "DELETE FROM titulo_subtitulo WHERE id = $id";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Verifica se o subtitulo estah relacionado a (ou tem) composicoes.
     * @param inteiro $id - ID do subtitulo.
     * @return boolean
     */
    public function estaRelacionado($id) {
        $query = "SELECT * FROM subtitulo_composicao AS a WHERE a.id_tit_subtit = $id";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);

        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Verifica se determinado subtitulo ja existe.
     * @param OrcSubtitulo $subtitulo
     * @return boolean
     */
    public function subtituloJaExistente($subtitulo) {
        $query = "SELECT * FROM titulo_subtitulo AS a WHERE a.subtitulo = '" . $this->conexao->antiInject($subtitulo->getSubtitulo()) . "'";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Verifica se determinado subtitulo estah cadastrado para o titulo.
     * @param OrcSubtitulo $subtitulo
     * @return boolean
     */
    public function subtituloJaCadastrado($subtitulo) {
        $query = "SELECT * FROM titulo_subtitulo AS a WHERE a.subtitulo = '" . $this->conexao->antiInject($subtitulo->getSubtitulo()) . "' "
                . "AND a.id_orc_tit = " . $subtitulo->getTitulo()->getId();
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Retorna os subtitulos a serem adicionados num titulo.
     * @param inteiro $id_orc_tit
     * @param string $nome
     * @return array e/ou string vazia
     */
    public function pesquisarSubtitulosAdd($id_orc_tit, $nome) {
        $subtitulos = "";

        $query = "SELECT DISTINCT(a.subtitulo) FROM titulo_subtitulo AS a "
                . "WHERE a.subtitulo LIKE '$nome%' AND a.id_orc_tit != $id_orc_tit ORDER BY a.subtitulo ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);

        $x = 0;
        while ($subtitulo = $this->conexao->getRegistros($resultado)) {
            $x = $x + 1;
            if ($x == 1) {
                $subtitulos = array(array($subtitulo["subtitulo"] => $subtitulo["subtitulo"]));
            } else {
                $subtitulos[] = array($subtitulo["subtitulo"] => $subtitulo["subtitulo"]);
            }
        }

        if ($qtdRegistros > 0) {
            return $subtitulos;
        } else {
            return "";
        }
    }

    /**
     * Retorna um 'select' com todos subtitulos de um orcamento.
     * @param inteiro $id_orc
     * @return string
     */
    public function getSelectSubtitulos($id_orc) {
        $conteudo_html = "";

        $query = "SELECT a.id, a.subtitulo FROM titulo_subtitulo AS a, orc_titulo AS b "
                . "WHERE a.id_orc_tit = b.id AND b.id_orc = $id_orc ORDER BY a.num_item ASC";
        $resultado = $this->conexao->executaQuery($query);

        $conteudo_html .= "<select name = 'tit_subtitulos' id = 'tit_subtitulos' class='selectTitulos'>";
        while ($titulos = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<option value = '" . $titulos["id"] . "'>" . $titulos["subtitulo"] . "</option>";
        }
        $conteudo_html .= "</select>";

        return $conteudo_html;
    }

    public function getTotal($id_tit_subtit, $encargo_social, $bdi, $is_servico) {
        $orcCompInsumoDAO = new OrcComposicaoInsumoDAO();
        $total = 0;
        
        $query = "SELECT a.id_orc_comp, b.quantidade "
                . "FROM subtitulo_composicao AS a, orc_composicao AS b "
                . "WHERE a.id_orc_comp = b.id AND a.id_tit_subtit = $id_tit_subtit";
        $resultado = $this->conexao->executaQuery($query);
        $qtd = $this->conexao->getQtdRegistros($resultado);
        if ($qtd > 0) {
            while ($composicao = $this->conexao->getRegistros($resultado)) {
                $totalInsumos = $orcCompInsumoDAO->getTotalInsumos($composicao["id_orc_comp"], $encargo_social, $bdi, $is_servico);
                $total += ($totalInsumos * $composicao["quantidade"]);
            }
            return $total;
        }
        return $total;
    }

}

?>
