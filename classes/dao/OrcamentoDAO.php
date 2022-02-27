<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/NumeroUtil.php";

class OrcamentoDAO {

    private $conexao;
    private $funcionario;

    public function OrcamentoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    public function getOrcamento($id) {
        $query = "SELECT a.*, DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS dtCadastro FROM orcamento AS a WHERE a.id = $id";
        $resultado = $this->conexao->executaQuery($query);
        return $this->conexao->getRegistros($resultado);
    }

    /**
     * Lista orçamentos.
     * @return html
     */
    public function listarOrcamentos($id_modulo) {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_vazio = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela' class='tablesorter'>";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>ID</th>";
        $conteudo_html_topo .= "<th>OBRA</th>";
        $conteudo_html_topo .= "<th>CLIENTE</th>";
        $conteudo_html_topo .= "<th>DATA DE CADASTRO</th>";
        $conteudo_html_topo .= "<th>VER</th>";
        $conteudo_html_topo .= "<th>EXCLUIR</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.*, DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS dtCadastro FROM orcamento AS a ORDER BY a.nome_obra";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        while ($orcamento = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . $orcamento["id"] . "</td>";
            $conteudo_html .= "<td>" . $orcamento["nome_obra"] . "</td>";
            $conteudo_html .= "<td>" . $orcamento["nome_cliente"] . "</td>";
            $conteudo_html .= "<td>" . $orcamento["dtCadastro"] . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<a href='orcamento.php?id=$id_modulo&id_orc=" . $orcamento["id"] . "'/><img src='../../imagens/icones/editar.png' title='Editar' alt='' class='editarOrc tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<input type='hidden' name='id_orc' id='id_orc' value='" . $orcamento["id"] . "'/>";
            $conteudo_html .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirOrc tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "</tr>";
        }
        $conteudo_html .= "</tbody>";

        $conteudo_html_rodape .= "<tfoot> <tr> <th colspan='6'>";
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

        $conteudo_vazio .= "<tbody><tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr></tbody>";

        if ($qtdRegistros > 0) {
            return $conteudo_html_topo . $conteudo_html . $conteudo_html_rodape;
        } else {
            return $conteudo_html_topo . $conteudo_vazio . $conteudo_html_rodape;
        }
    }

    /**
     * Cadastra orcamento.
     * @param array $orcamento
     * @return boolean
     */
    public function cadastrar($orcamento) {
        $query = "INSERT INTO orcamento (nome_obra, nome_cliente, local, area, bdi, encargo_social, usuario_cadastro, data_cadastro) "
                . "VALUES ('" . $this->conexao->antiInject($orcamento["nome_obra"]) . "', "
                . "'" . $this->conexao->antiInject($orcamento["nome_cliente"]) . "', "
                . "'" . $this->conexao->antiInject($orcamento["local"]) . "', "
                . "'" . NumeroUtil::formatar($orcamento["area"], NumeroUtil::NUMERO_USA) . "', "
                . "'" . NumeroUtil::formatar($orcamento["bdi"], NumeroUtil::NUMERO_USA) . "', "
                . "'" . NumeroUtil::formatar($orcamento["encargo_social"], NumeroUtil::NUMERO_USA) . "', "
                . $this->funcionario->getCodigo() . ", "
                . "NOW())"; //echo $query;
        return $this->conexao->executaQuery($query);
    }

    /**
     * Edita orçamento.
     * @param array $orcamento
     * @return boolean
     */
    public function editar($orcamento) {
        $query = "UPDATE orcamento "
                . "SET nome_obra = '" . $this->conexao->antiInject($orcamento["nome_obra"]) . "', "
                . "nome_cliente = '" . $this->conexao->antiInject($orcamento["nome_cliente"]) . "', "
                . "local = '" . $this->conexao->antiInject($orcamento["local"]) . "', "
                . "area = " . NumeroUtil::formatar($orcamento["area"], NumeroUtil::NUMERO_USA) . ", "
                . "bdi = " . NumeroUtil::formatar($orcamento["bdi"], NumeroUtil::NUMERO_USA) . ", "
                . "encargo_social = " . NumeroUtil::formatar($orcamento["encargo_social"], NumeroUtil::NUMERO_USA) . ", "
                . "usuario_atualizacao = " . $this->funcionario->getCodigo() . ", "
                . "data_atualizacao = NOW() WHERE id = " . $orcamento["id_orc_cad"]; //echo $query;
        return $this->conexao->executaQuery($query);
    }

    /**
     * Exclui orçamento.
     * @param inteiro $id_orc
     * @return boolean
     */
    public function excluir($id_orc) {
        $query = "DELETE FROM orcamento WHERE id = $id_orc";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Verifica se o orçamento tem alguma composição.
     * @param inteiro $id_orc
     * @return boolean
     */
    public function temComposicao($id_orc) {
        $query = "SELECT * FROM orc_composicao AS a WHERE a.id_orc = $id_orc";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Edita o B. D. I.
     * @param inteiro $id_orc
     * @param inteiro $bdi
     * @return boolean
     */
    public function editarBDI($id_orc, $bdi) {
        $query = "UPDATE orcamento "
                . "SET bdi = " . NumeroUtil::formatar($bdi, NumeroUtil::NUMERO_USA) . ", "
                . "usuario_atualizacao = " . $this->funcionario->getCodigo() . ", "
                . "data_atualizacao = NOW() WHERE id = " . $id_orc;
        return $this->conexao->executaQuery($query);
    }

    /**
     * Edita Encargos Sociais.
     * @param inteiro $id_orc
     * @param inteiro $enc_soc
     * @return boolean
     */
    public function editarES($id_orc, $enc_soc) {
        $query = "UPDATE orcamento "
                . "SET encargo_social = " . NumeroUtil::formatar($enc_soc, NumeroUtil::NUMERO_USA) . ", "
                . "usuario_atualizacao = " . $this->funcionario->getCodigo() . ", "
                . "data_atualizacao = NOW() WHERE id = " . $id_orc;
        return $this->conexao->executaQuery($query);
    }

    /**
     * Adiciona uma composicao ao orcamento.
     * @param inteiro $id_orc
     * @param inteiro $codigo
     * @return boolean
     */
    public function adicionarComposicao($id_orc, $codigo) {
        $qtdOrcComIns = 0;

        $queryCompAdd = "INSERT INTO orc_composicao (id_orc, id_comp) VALUES ($id_orc , $codigo)";
        if ($this->conexao->executaQuery($queryCompAdd)) {
            $queryOrcComp = "SELECT a.id AS idOrcComp FROM orc_composicao AS a WHERE a.id_comp = $codigo";
            $resultOrcComp = $this->conexao->executaQuery($queryOrcComp);
            $orcComp = $this->conexao->getRegistros($resultOrcComp);

            $queryCompIns = "SELECT a.id_ins FROM comp_insumo AS a WHERE a.id_comp = $codigo";
            $resultCompIns = $this->conexao->executaQuery($queryCompIns);
            $qtdCompIns = $this->conexao->getQtdRegistros($resultCompIns);

            while ($compIns = $this->conexao->getRegistros($resultCompIns)) {
                $queryOrcCompIns = "INSERT INTO orc_comp_insumo (id_orc_comp, id_ins, data_cadastro) "
                        . "VALUES (" . $orcComp["idOrcComp"] . ", " . $compIns["id_ins"] . ", NOW())";
                if ($this->conexao->executaQuery($queryOrcCompIns)) {
                    $qtdOrcComIns++;
                }
            }

            if ($qtdOrcComIns == $qtdCompIns) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Retorna os orcamentos para ve-los.
     * @param inteiro $id_orc
     * @param string $descricao
     * @return array
     */
    public function pesquisarOrcamentos($id_orc, $descricao) {
        $orcamentos = "";

        $query = "SELECT a.id, a.nome_obra FROM orcamento AS a WHERE a.nome_obra LIKE '$descricao%' AND a.id != $id_orc ORDER BY a.nome_obra ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);

        $x = 0;
        while ($orcamento = $this->conexao->getRegistros($resultado)) {
            $x = $x + 1;
            if ($x == 1) {
                $orcamentos = array(array($orcamento["id"] => $orcamento["nome_obra"]));
            } else {
                $orcamentos[] = array($orcamento["id"] => $orcamento["nome_obra"]);
            }
        }

        if ($qtdRegistros > 0) {
            return $orcamentos;
        } else {
            return "";
        }
    }

}

?>
