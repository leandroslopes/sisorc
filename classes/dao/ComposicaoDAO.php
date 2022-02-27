<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/OrcamentoDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";

class ComposicaoDAO {

    private $conexao;
    private $funcionario;

    public function ComposicaoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Lista as composicoes.
     * @return string
     */
    public function listarComposicoes($id_modulo) {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela_composicao' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>CÓDIGO</th>";
        $conteudo_html_topo .= "<th>NOME</th>";
        $conteudo_html_topo .= "<th>UNIDADE</th>";
        $conteudo_html_topo .= "<th>SERVIÇO</th>";
        $conteudo_html_topo .= "<th>TIPO</th>";
        $conteudo_html_topo .= "<th>VER</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.codigo, a.descricao, b.id AS idUni, b.nome AS unidade, c.id AS idServ, c.nome AS servico, d.id AS idTipo, d.nome AS tipo "
                . "FROM composicao AS a, unidade AS b, servico AS c, tipo AS d "
                . "WHERE a.id_uni = b.id AND a.id_serv = c.id AND a.id_tipo = d.id ORDER BY d.nome ASC, a.descricao ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        while ($composicao = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . $composicao["codigo"] . "</td>";
            $conteudo_html .= "<td>" . $composicao["descricao"] . "</td>";
            $conteudo_html .= "<td>" . $composicao["unidade"] . "</td>";
            $conteudo_html .= "<td>" . $composicao["servico"] . "</td>";
            $conteudo_html .= "<td>" . $composicao["tipo"] . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<a href='ver_composicao.php?id=$id_modulo&codigo=" . $composicao["codigo"] . "'><img src='../../imagens/icones/editar.png' title='Editar' alt='' class='tam16' /></a>";
            $conteudo_html .= "</tr>";
        }
        $conteudo_html .= "</tbody>";

        $conteudo_html_rodape .= "<tfoot> <tr> <th colspan='6'>";
        $conteudo_html_rodape .= "<div id='paginacao_composicao' class='paginacao'> <form action=''>";
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
     * Verifica se determinada composicao se encontra cadastrada.
     * @param string $composicao
     * @return boolean
     */
    public function isCadastrado($composicao) {
        $query = "SELECT * FROM composicao AS a WHERE a.descricao = '" . $composicao["descricao"] . "' "
                . "AND a.id_uni = " . $composicao["unidade"] . " AND a.id_tipo = " . $composicao["tipo"];
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Cadastra composicao.
     * @param array $composicao
     * @return boolean
     */
    public function cadastrar($composicao) {
        $query = "INSERT INTO composicao (descricao, id_uni, id_tipo) "
                . "VALUES ('" . $this->conexao->antiInject($composicao["descricao"]) . "', "
                . $composicao["unidade"] . ", "
                . $composicao["tipo"] . ")";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Cadastra composicao como avulsa e adiciona no orcamento.
     * @param array $composicao
     * @return boolean
     */
    public function cadastrarNova($composicao) {
        $composicao_nova = "";

        $query = "INSERT INTO composicao (descricao, id_uni, id_tipo) "
                . "VALUES ('" . $this->conexao->antiInject($composicao["descricao"]) . "', "
                . $composicao["unidade"] . ", "
                . $composicao["tipo"] . ")";

        if ($this->conexao->executaQuery($query)) {
            $orcamentoDAO = new OrcamentoDAO();
            $composicao_nova = $this->getComposicaoPorNome($composicao["descricao"]);
            if ($orcamentoDAO->adicionarComposicao($composicao["id_orc"], $composicao_nova["codigo"])) {
                return true;
            } else {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Edita composicao.
     * @param array $composicao
     * @return boolean
     */
    public function editar($composicao) {
        $query = "UPDATE composicao "
                . "SET descricao = '" . $this->conexao->antiInject($composicao["descricao"]) . "', "
                . "id_uni = " . $composicao["unidade"] . ", "
                . "id_tipo = " . $composicao["tipo"] . " "
                . "WHERE codigo = " . $composicao["codigo"];
        return $this->conexao->executaQuery($query);
    }

    /**
     * Retorna uma composicao.
     * @param inteiro $codigo
     * @return array
     */
    public function getComposicao($codigo) {
        $composicao = "";
        if (!empty($codigo)) {
            $query = "SELECT * FROM composicao AS a WHERE a.codigo = $codigo ORDER BY a.descricao";
            $resultado = $this->conexao->executaQuery($query);
            $composicao = $this->conexao->getRegistros($resultado);
        }
        return $composicao;
    }

    /**
     * Retorna uma composicao pela descricao.
     * @param string $descricao
     * @return array
     */
    public function getComposicaoPorNome($descricao) {
        $query = "SELECT * FROM composicao AS a WHERE a.descricao = '" . $this->conexao->antiInject($descricao) . "'";
        $resultado = $this->conexao->executaQuery($query);
        return $this->conexao->getRegistros($resultado);
    }

    /**
     * Retorna as composicoes para adicionar no orcamento.
     * @param inteiro $id_orc
     * @param string $descricao
     * @return array
     */
    public function getComposicoesAdd($id_orc, $descricao) {
        $composicoes = "";

        $query = "SELECT a.codigo, a.descricao FROM composicao AS a WHERE a.descricao LIKE '$descricao%' "
                . "AND a.codigo NOT IN (SELECT a.id_comp FROM orc_composicao AS a WHERE a.id_orc = $id_orc) ORDER BY a.descricao ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);

        $x = 0;
        while ($composicao = $this->conexao->getRegistros($resultado)) {
            $x = $x + 1;
            if ($x == 1) {
                $composicoes = array(array($composicao["codigo"] => $composicao["descricao"]));
            } else {
                $composicoes[] = array($composicao["codigo"] => $composicao["descricao"]);
            }
        }

        if ($qtdRegistros > 0) {
            return $composicoes;
        } else {
            return "";
        }
    }

    /**
     * Retorna as composicoes para ve-las.
     * @param string $descricao
     * @return array
     */
    public function pesquisarComposicoes($descricao) {
        $composicoes = "";

        $query = "SELECT a.codigo, a.descricao FROM composicao AS a WHERE a.descricao LIKE '$descricao%' ORDER BY a.descricao ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);

        $x = 0;
        while ($composicao = $this->conexao->getRegistros($resultado)) {
            $x = $x + 1;
            if ($x == 1) {
                $composicoes = array(array($composicao["codigo"] => $composicao["descricao"]));
            } else {
                $composicoes[] = array($composicao["codigo"] => $composicao["descricao"]);
            }
        }

        if ($qtdRegistros > 0) {
            return $composicoes;
        } else {
            return "";
        }
    }

    /**
     * Listar os insumos de uma composicao.
     * @param inteiro $id_comp
     * @return string
     */
    public function listarInsumos($id_comp) {
        $conteudo_html = "";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.id AS idCompIns, b.codigo, b.descricao, c.id AS idUni, c.nome AS unidade, d.id AS idServ, d.nome AS servico, e.id AS idTipo, e.nome AS tipo "
                . "FROM comp_insumo AS a, insumo AS b, unidade AS c, servico AS d, tipo AS e "
                . "WHERE a.id_ins = b.codigo AND b.id_uni = c.id AND b.id_serv = d.id AND b.id_tipo = e.id AND a.id_comp = $id_comp "
                . "ORDER BY d.nome ASC, b.descricao ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        while ($insumo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td><input type='hidden' name='idCompIns' id='idCompIns' value='" . $insumo["idCompIns"] . "'/>" . $insumo["codigo"] . "</td>";
            $conteudo_html .= "<td><input type='hidden' name='descCompIns' id='descCompIns' value='" . $insumo["descricao"] . "'/>" . $insumo["descricao"] . "</td>";
            $conteudo_html .= "<td>" . $insumo["unidade"] . "</td>";
            $conteudo_html .= "<td>" . $insumo["servico"] . "</td>";
            $conteudo_html .= "<td>" . $insumo["tipo"] . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirAddIns tam16'/>";
            $conteudo_html .= "</tr>";
        }
        $conteudo_html .= "</tbody></table>";

        $conteudo_vazio = "<tbody><tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr></tbody>";

        if ($qtdRegistros > 0) {
            return $conteudo_html;
        } else {
            return $conteudo_vazio;
        }
    }

    /**
     * Retorna os insumos para adicionar na composicao.
     * @param inteiro $id_comp
     * @param string $descricao
     * @return array
     */
    public function pesquisarInsumosAdd($id_comp, $descricao) {
        $insumos = "";

        $query = "SELECT a.codigo, a.descricao FROM insumo AS a WHERE a.codigo NOT IN (SELECT a.id_ins FROM comp_insumo AS a WHERE a.id_comp = $id_comp) "
                . "AND a.descricao LIKE '$descricao%' ORDER BY a.descricao ASC";
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

    /**
     * Adiciona um insumo para uma composicao.
     * @param inteiro $id_comp
     * @param inteiro $id_ins
     * @return boolean
     */
    public function adicionarInsumo($id_comp, $id_ins) {
        $query = "INSERT INTO comp_insumo (id_comp, id_ins) VALUES ($id_comp, $id_ins)";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Exclui um insumo de uma composicao.
     * @param inteiro $id_comp_ins
     * @return boolean
     */
    public function excluirInsumo($id_comp_ins) {
        $query = "DELETE FROM comp_insumo WHERE id = $id_comp_ins";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Retorna as composicoes para copiar no orcamento.
     * @param inteiro $id_orc
     * @return array
     */
    public function getComposicoesCop($id_orc) {
        $composicoes = "";

        $query = "SELECT a.id, b.descricao "
                . "FROM orc_composicao AS a, composicao AS b "
                . "WHERE a.id_comp = b.codigo AND a.id_orc = $id_orc "
                . "ORDER BY b.descricao ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);

        $x = 0;
        while ($composicao = $this->conexao->getRegistros($resultado)) {
            $x = $x + 1;
            if ($x == 1) {
                $composicoes = array(array($composicao["id"] => $composicao["descricao"]));
            } else {
                $composicoes[] = array($composicao["id"] => $composicao["descricao"]);
            }
        }

        if ($qtdRegistros > 0) {
            return $composicoes;
        } else {
            return "";
        }
    }

}
?>

