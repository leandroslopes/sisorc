<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/OrcComposicaoInsumoDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/SubtituloDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/NumeroUtil.php";

class TituloDAO {

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function TituloDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Adiciona um titulo no orcamento.
     * @param OrcTitulo $orc_titulo
     * @return boolean
     */
    public function adicionar($orc_titulo) {
        $query = "INSERT INTO orc_titulo (id_orc, titulo, num_item) "
                . "VALUES (" . $orc_titulo->getOrcamento()->getId() . ", '" . $this->conexao->antiInject($orc_titulo->getTitulo()) . "', "
                . "'" . $this->conexao->antiInject($orc_titulo->getNumeroItem()) . "')";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Edita um titulo do orcamento.
     * @param OrcTitulo $orc_titulo
     * @return boolean
     */
    public function editar($orc_titulo) {
        $query = "UPDATE orc_titulo SET titulo = '" . $this->conexao->antiInject($orc_titulo->getTitulo()) . "', "
                . "num_item = '" . $this->conexao->antiInject($orc_titulo->getNumeroItem()) . "' "
                . "WHERE id = " . $orc_titulo->getId();
        return $this->conexao->executaQuery($query);
    }

    /**
     * Exclui titulo.
     * @param inteiro $id - ID do titulo.
     * @return boolean
     */
    public function excluir($id) {
        $query = "DELETE FROM orc_titulo WHERE id = $id";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Verifica se o titulo esta relacionado (ou tem) subtitulos e/ou composicoes.
     * @param inteiro $id - ID do titulo.
     * @return boolean
     */
    public function estaRelacionado($id) {
        $query1 = "SELECT * FROM titulo_subtitulo AS a WHERE a.id_orc_tit = $id";
        $resultado1 = $this->conexao->executaQuery($query1);
        $qtdReg1 = $this->conexao->getQtdRegistros($resultado1);

        $query2 = "SELECT * FROM titulo_composicao AS a WHERE a.id_orc_tit = $id";
        $resultado2 = $this->conexao->executaQuery($query2);
        $qtdReg2 = $this->conexao->getQtdRegistros($resultado2);

        $qtdTotal = $qtdReg1 + $qtdReg2;

        if ($qtdTotal > 0) {
            return true;
        }
        return false;
    }

    /**
     * Lista os itens (titulos, subtitulos e composicoes) da planilha orcamentaria.
     * @param inteiro $id_orc
     * @param decimal $encargo_social
     * @param decimal $bdi
     * @return string
     */
    public function listarTitulos($id_orc, $encargo_social, $bdi) {
        $orcCompInsumoDAO = new OrcComposicaoInsumoDAO();
        $orcSubtituloDAO = new SubtituloDAO();
        $conteudo_html = "";
        $conteudo_vazio = "";
        $estilo_linha = "";

        $queryTitulos = "SELECT a.id, a.num_item, a.titulo FROM orc_titulo AS a WHERE a.id_orc = $id_orc ORDER BY a.num_item ASC";
        $resultadoTitulos = $this->conexao->executaQuery($queryTitulos);
        $qtdTitulos = $this->conexao->getQtdRegistros($resultadoTitulos);
        if ($qtdTitulos > 0) {
            while ($titulo = $this->conexao->getRegistros($resultadoTitulos)) {
                $conteudo_html .= "<tr class='tituloItem negrito'>";
                $conteudo_html .= "<td><input type='hidden' name='id_orc_tit' id='id_orc_tit' value='" . $titulo["id"] . "'/>";
                $conteudo_html .= "<label>" . $titulo["num_item"] . "</label></td>";
                $conteudo_html .= "<td><a class='eddTitulo cursor'>" . $titulo["titulo"] . "</a></td>";
                $conteudo_html .= "<td>&nbsp;</td>";
                $conteudo_html .= "<td>&nbsp;</td>";
                $conteudo_html .= "<td>&nbsp;</td>";

                $conteudo_html .= "<td>" . NumeroUtil::formatar($this->getTotal($titulo["id"], $encargo_social, $bdi, FALSE), NumeroUtil::NUMERO_BRA) . "</td>";

                $conteudo_html .= "<td class='celulaIcone'><img src='../../imagens/icones/excluir.png' alt='Excluir' class='excTitulo cursor tam16'/></td>";
                $conteudo_html .= "</tr>";

                $query = "(SELECT a.id, a.id_orc_comp, a.num_item, c.descricao, d.nome AS unidade, b.quantidade "
                        . "FROM titulo_composicao AS a, orc_composicao AS b, composicao AS c, unidade AS d "
                        . "WHERE a.id_orc_comp = b.id AND b.id_comp = c.codigo AND c.id_uni = d.id AND a.id_orc_tit = " . $titulo["id"] . ") "
                        . "UNION (SELECT a.id, @id_orc_comp := 0, a.num_item, a.subtitulo AS descricao, @unidade := 0, @quantidade := 0 "
                        . "FROM titulo_subtitulo AS a WHERE a.id_orc_tit = " . $titulo["id"] . ") ORDER BY num_item ASC ";
                $resultado = $this->conexao->executaQuery($query);
                $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
                if ($qtdRegistros > 0) {
                    $estilo_linha = "low";
                    while ($array = $this->conexao->getRegistros($resultado)) {
                        if ($array["id_orc_comp"] != 0) {
                            $conteudo_html .= "<tr class = '$estilo_linha'>";
                            $conteudo_html .= "<td>";
                            $conteudo_html .= "<input type='hidden' name='id_orc_comp' id='id_orc_comp' value='" . $array["id_orc_comp"] . "'/>";
                            $conteudo_html .= "<input type='hidden' name='id_comp' id='id_comp' value='" . $array["id"] . "'/>";
                            $conteudo_html .= "<input type='hidden' name='tipo_comp' id='tipo_comp' value='tit_comp'/>";
                            $conteudo_html .= "<label>" . $array["num_item"] . "</label>";
                            $conteudo_html .= "</td>";
                            $conteudo_html .= "<td>" . $array["descricao"] . "</td>";
                            $conteudo_html .= "<td>" . $array["unidade"] . "</td>";
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($array["quantidade"], NumeroUtil::NUMERO_BRA) . "</td>";

                            $preco = $orcCompInsumoDAO->getTotalInsumosESubcomposicoes($array["id_orc_comp"], $encargo_social, $bdi, FALSE);
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($preco, NumeroUtil::NUMERO_BRA) . "</td>";

                            $total = NumeroUtil::multiplicar($array["quantidade"], $preco);
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($total, NumeroUtil::NUMERO_BRA) . "</td>";

                            $conteudo_html .= "<td class='celulaIcone'>";
                            $conteudo_html .= "<img src='../../imagens/icones/excluir.png' alt='Excluir' class='excTitComp cursor tam16'/>";
                            $conteudo_html .= "<img src='../../imagens/icones/editar.png' alt='Editar' class='eddTitComp cursor tam16'/>";
                            $conteudo_html .= "</td>";
                            $conteudo_html .= "</tr>";

                            $estilo_linha == "high" ? $estilo_linha = "low" : $estilo_linha = "high";
                        } else {
                            $conteudo_html .= "<tr class = 'tituloItem textoEsq'>";
                            $conteudo_html .= "<td><input type='hidden' name='id_subtit' id='id_subtit' value='" . $array["id"] . "'/>";
                            $conteudo_html .= "<label>" . $array["num_item"] . "</label></td>";
                            $conteudo_html .= "<td><a class='eddSubtit cursor'>" . $array["descricao"] . "</a></td>";
                            $conteudo_html .= "<td>&nbsp;</td>";
                            $conteudo_html .= "<td>&nbsp;</td>";
                            $conteudo_html .= "<td>&nbsp;</td>";

                            $total_subtitulo = $orcSubtituloDAO->getTotal($array["id"], $encargo_social, $bdi, FALSE);
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($total_subtitulo, NumeroUtil::NUMERO_BRA) . "</td>";

                            $conteudo_html .= "<td class='celulaIcone'><img src='../../imagens/icones/excluir.png' alt='Excluir' class='excSubtit cursor tam16'/></td>";
                            $conteudo_html .= "</tr>";

                            $querySubtitComp = "SELECT a.id, a.id_orc_comp, a.num_item, c.descricao, d.nome AS unidade, b.quantidade "
                                    . "FROM subtitulo_composicao AS a, orc_composicao AS b, composicao AS c, unidade AS d "
                                    . "WHERE a.id_orc_comp = b.id AND b.id_comp = c.codigo AND c.id_uni = d.id AND a.id_tit_subtit = " . $array["id"]
                                    . " ORDER BY a.num_item";
                            $resultSubtitComp = $this->conexao->executaQuery($querySubtitComp);
                            $qtdSubtitComp = $this->conexao->getQtdRegistros($resultSubtitComp);
                            if ($qtdSubtitComp > 0) {
                                $estilo_linha = "low";
                                while ($subtit_comp = $this->conexao->getRegistros($resultSubtitComp)) {
                                    $conteudo_html .= "<tr class = '$estilo_linha'>";
                                    $conteudo_html .= "<td>";
                                    $conteudo_html .= "<input type='hidden' name='id_orc_comp' id='id_orc_comp' value='" . $subtit_comp["id_orc_comp"] . "'/>";
                                    $conteudo_html .= "<input type='hidden' name='id_comp' id='id_comp' value='" . $subtit_comp["id"] . "'/>";
                                    $conteudo_html .= "<input type='hidden' name='tipo_comp' id='tipo_comp' value='subtit_comp'/>";
                                    $conteudo_html .= "<label>" . $subtit_comp["num_item"] . "</label>";
                                    $conteudo_html .= "</td>";
                                    $conteudo_html .= "<td>" . $subtit_comp["descricao"] . "</td>";
                                    $conteudo_html .= "<td>" . $subtit_comp["unidade"] . "</td>";
                                    $conteudo_html .= "<td>" . NumeroUtil::formatar($subtit_comp["quantidade"], NumeroUtil::NUMERO_BRA) . "</td>";

                                    $preco = $orcCompInsumoDAO->getTotalInsumos($subtit_comp["id_orc_comp"], $encargo_social, $bdi, FALSE);
                                    $conteudo_html .= "<td>" . NumeroUtil::formatar($preco, NumeroUtil::NUMERO_BRA) . "</td>";

                                    $total = NumeroUtil::multiplicar($subtit_comp["quantidade"], $preco);
                                    $conteudo_html .= "<td>" . NumeroUtil::formatar($total, NumeroUtil::NUMERO_BRA) . "</td>";

                                    $conteudo_html .= "<td class='celulaIcone'>";
                                    $conteudo_html .= "<img src='../../imagens/icones/excluir.png' alt='Excluir' class='excSubtitComp cursor tam16'/>";
                                    $conteudo_html .= "<img src='../../imagens/icones/editar.png' alt='Editar' class='eddSubtitComp cursor tam16'/>";
                                    $conteudo_html .= "</td>";
                                    $conteudo_html .= "</tr>";

                                    $estilo_linha == "low" ? $estilo_linha = "high" : $estilo_linha = "low";
                                }
                            } else {
                                $conteudo_html .= "<tr class = 'low textoEsq'><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td></td></tr>";
                            }
                        }
                    }
                } else {
                    $conteudo_html .= "<tr class = 'low textoEsq'><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td></td></tr>";
                }
            }
            return $conteudo_html;
        } else {
            $conteudo_vazio .= "<tr class = 'low textoEsq'><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td></td></tr>";
            return $conteudo_vazio;
        }
    }

    /**
     * Verifica se determinado titulo ja existe.
     * @param OrcTitulo $orc_titulo
     * @return boolean
     */
    public function tituloJaExistente($orc_titulo) {
        $query = "SELECT * FROM orc_titulo AS a WHERE a.titulo = '" . $this->conexao->antiInject($orc_titulo->getTitulo()) . "'";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Verifica se determinado titulo estah cadastrado no orcamento.
     * @param OrcTitulo $orc_titulo
     * @return boolean
     */
    public function tituloJaCadastrado($orc_titulo) {
        $query1 = "SELECT * FROM orc_titulo AS a WHERE a.titulo = '" . $this->conexao->antiInject($orc_titulo->getTitulo()) . "' "
                . "AND a.id_orc = " . $orc_titulo->getOrcamento()->getId();
        $resultado1 = $this->conexao->executaQuery($query1);
        $qtdRegistros1 = $this->conexao->getQtdRegistros($resultado1);

        $query2 = "SELECT * FROM orc_titulo AS a WHERE a.num_item = '" . $this->conexao->antiInject($orc_titulo->getNumeroItem()) . "' "
                . "AND a.id_orc = " . $orc_titulo->getOrcamento()->getId();
        $resultado2 = $this->conexao->executaQuery($query2);
        $qtdRegistros2 = $this->conexao->getQtdRegistros($resultado2);

        $qtdTotal = $qtdRegistros1 + $qtdRegistros2;

        if ($qtdTotal > 0) {
            return true;
        }
        return false;
    }

    /**
     * Retorna os titulos a serem adicionados num orcamento.
     * @param inteiro $id_orc
     * @param string $nome
     * @return array
     */
    public function pesquisarTitulosAdd($id_orc, $nome) {
        $titulos = "";

        $query = "SELECT DISTINCT(a.titulo) FROM orc_titulo AS a WHERE a.titulo LIKE '$nome%' AND a.id_orc != $id_orc ORDER BY a.titulo ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);

        $x = 0;
        while ($titulo = $this->conexao->getRegistros($resultado)) {
            $x = $x + 1;
            if ($x == 1) {
                $titulos = array(array($titulo["titulo"] => $titulo["titulo"]));
            } else {
                $titulos[] = array($titulo["titulo"] => $titulo["titulo"]);
            }
        }

        if ($qtdRegistros > 0) {
            return $titulos;
        } else {
            return "";
        }
    }

    /**
     * Retorna um 'select' com os titulos de um orcamento.
     * @param inteiro $id_orc
     * @return string
     */
    public function getSelectOrcTitulos($id_orc) {
        $conteudo_html = "";

        $query = "SELECT a.id, a.titulo FROM orc_titulo AS a WHERE a.id_orc = $id_orc ORDER BY a.num_item ASC";
        $resultado = $this->conexao->executaQuery($query);

        $conteudo_html .= "<select name = 'orc_titulos' id = 'orc_titulos' class='selectTitulos'>";
        while ($titulos = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<option value = '" . $titulos["id"] . "'>" . $titulos["titulo"] . "</option>";
        }
        $conteudo_html .= "</select>";

        return $conteudo_html;
    }

    /**
     * Retorna o total do titulo (soma de todas as composicoes do mesmo).
     * @param inteiro $id_orc_titulo
     * @param decimal $encargo_social
     * @param decimal $bdi
     * @param boolean $is_servico
     * @return decimal
     */
    public function getTotal($id_orc_titulo, $encargo_social, $bdi, $is_servico) {
        $orcCompInsumoDAO = new OrcComposicaoInsumoDAO();
        $total = 0;

        $query1 = "SELECT a.id FROM titulo_subtitulo AS a WHERE a.id_orc_tit = $id_orc_titulo";
        $resultado1 = $this->conexao->executaQuery($query1);
        $qtd1 = $this->conexao->getQtdRegistros($resultado1);
        if ($qtd1 > 0) {
            while ($subtitulo = $this->conexao->getRegistros($resultado1)) {
                $query2 = "SELECT a.id_orc_comp, b.quantidade "
                        . "FROM subtitulo_composicao AS a, orc_composicao AS b "
                        . "WHERE a.id_orc_comp = b.id AND a.id_tit_subtit = " . $subtitulo["id"];
                $resultado2 = $this->conexao->executaQuery($query2);
                $qtd2 = $this->conexao->getQtdRegistros($resultado2);
                if ($qtd2 > 0) {
                    while ($composicao = $this->conexao->getRegistros($resultado2)) {
                        $totalComposicao = $orcCompInsumoDAO->getTotalInsumosESubcomposicoes($composicao["id_orc_comp"], $encargo_social, $bdi, $is_servico);
                        $totalComposicao *= $composicao["quantidade"];
                        $total += $totalComposicao;
                    }
                }
            }
        }
        
        $query3 = "SELECT a.id_orc_comp, b.quantidade "
                . "FROM titulo_composicao AS a, orc_composicao AS b "
                . "WHERE a.id_orc_comp = b.id AND a.id_orc_tit = $id_orc_titulo";
        $resultado3 = $this->conexao->executaQuery($query3);
        $qtd3 = $this->conexao->getQtdRegistros($resultado3);
        if ($qtd3 > 0) {
            while ($composicao = $this->conexao->getRegistros($resultado3)) {
                $totalComposicao = $orcCompInsumoDAO->getTotalInsumosESubcomposicoes($composicao["id_orc_comp"], $encargo_social, $bdi, $is_servico);
                $totalComposicao *= $composicao["quantidade"];
                $total += $totalComposicao;
            }
        }
        
        return $total;
    }

    public function getTotalOrcamento($id_orc, $encargo_social, $bdi, $is_servico) {
        $total = 0;

        $query = "SELECT a.id FROM orc_titulo AS a WHERE a.id_orc = $id_orc";
        $resultado = $this->conexao->executaQuery($query);
        $qtd = $this->conexao->getQtdRegistros($resultado);
        if ($qtd > 0) {
            while ($titulo = $this->conexao->getRegistros($resultado)) {
                $total += $this->getTotal($titulo["id"], $encargo_social, $bdi, $is_servico);
            }
        }

        return $total;
    }

}

?>