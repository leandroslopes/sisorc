<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/TituloDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/OrcamentoDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Cronograma.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/CronogramaCabecalho.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/NumeroUtil.php";

class CronogramaDAO {

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function CronogramaDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Gera e/ou mostra o cronograma para cadastrar e/ou editar.
     * @param Cronograma $cronograma
     * @return string
     */
    public function gerar($cronograma) {
        $orcamentoDAO = new OrcamentoDAO();
        $tituloDAO = new TituloDAO();
        $conteudo_html = "";
        $cabecalho = "";

        $array_orcamento = $orcamentoDAO->getOrcamento($cronograma->getOrcamento()->getId());

        $conteudo_html .= "<form name='frmCadastrarCronograma' action='' method='post'>";
        $conteudo_html .= "<input type='hidden' name='id_orc' id='id_orc' value='" . $cronograma->getOrcamento()->getId() . "'/>";
        $conteudo_html .= "<input type='hidden' name='id_cronograma' id='id_cronograma' value='" . $cronograma->getId() . "'/>";
        $conteudo_html .= "<input type='hidden' name='qtd' id='qtd' value='" . $cronograma->getQuantidadeCabecalho() . "'/>";
        $conteudo_html .= "<table id='tabela'>";
        $conteudo_html .= "<thead>";
        $conteudo_html .= "<tr class='high negrito'>";
        $conteudo_html .= "<th class='textoEsq'>ITEM</th>";
        $conteudo_html .= "<th class='textoEsq'>SERVIÇO</th>";
        $conteudo_html .= "<th>VALOR</th>";

        $id_cabecalho = $cronograma->getCabecalho()->getId();
        for ($i = 1; $i <= $cronograma->getQuantidadeCabecalho(); $i++) {
            if ($i == 1) {
                if ($id_cabecalho == 1) {
                    $cabecalho = $i . " MÊS";
                } else if ($id_cabecalho == 2) {
                    $cabecalho = $i . " SEMANA";
                } else if ($id_cabecalho == 3) {
                    $cabecalho = $i . " DIA";
                }
            } else {
                if ($id_cabecalho == 1) {
                    $cabecalho = $i . " MESES";
                } else if ($id_cabecalho == 2) {
                    $cabecalho = $i . " SEMANAS";
                } else if ($id_cabecalho == 3) {
                    $cabecalho = $i . " DIAS";
                }
            }
            $conteudo_html .= "<th>$cabecalho</th>";
        }

        $conteudo_html .= "<th>T. P.(%)</th>";
        $conteudo_html .= "</thead>";

        $estilo_linha = "low";

        $query = "SELECT * FROM orc_titulo AS a WHERE a.id_orc = " . $cronograma->getOrcamento()->getId() . " ORDER BY a.num_item";
        $resultado = $this->conexao->executaQuery($query);
        while ($titulo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr class='$estilo_linha'>";
            $conteudo_html .= "<td>" . $titulo["num_item"] . "</td>";
            $conteudo_html .= "<td>" . $titulo["titulo"] . "</td>";

            $valor = $tituloDAO->getTotal($titulo["id"], $array_orcamento["encargo_social"], $array_orcamento["bdi"], FALSE);
            $conteudo_html .= "<td class='textoCentro'>";
            $conteudo_html .= "<input type='hidden' name='total' id='total' value='$valor'/>";
            $conteudo_html .= NumeroUtil::formatar($valor, NumeroUtil::NUMERO_BRA);
            $conteudo_html .= "</td>";

            $array_porcentagens = $this->getPorcentagens($titulo["id"]);
            $porcentagem = "";
            for ($j = 1; $j <= $cronograma->getQuantidadeCabecalho(); $j++) {
                $conteudo_html .= "<td class='textoCentro'>";

                if (!empty($array_porcentagens[$j - 1])) {
                    $porcentagem = $array_porcentagens[$j - 1];
                } else {
                    $porcentagem = "";
                }
                $conteudo_html .= "<input type='text' name='" . $titulo["id"] . "_" . $j . "' class='decimal porcentagem' size='4' value='$porcentagem'/>";
                $conteudo_html .= "<br /><br />";
                $conteudo_html .= "<label id='valorMes' class='negrito'></label>";
                $conteudo_html .= "</td>";
            }

            $conteudo_html .= "<td class='totalPercentual textoCentro'></td>";
            $conteudo_html .= "</tr>";

            $estilo_linha == "low" ? $estilo_linha = "high" : $estilo_linha = "low";
        }

        $conteudo_html .= "</table>";

        $botao = "";
        if (empty($cronograma->getId())) {
            $botao = "<input type='submit' name='btCadastrar' value='Cadastrar'/>";
        } else {
            $botao = "<input type='submit' name='btEditar' value='Editar'/>";
        }
        $conteudo_html .= "<br /> $botao &nbsp;&nbsp;";

        $conteudo_html .= "<input type='button' name='btCancelar' id='voltar' value='Cancelar'/>";
        $conteudo_html .= "</form>";

        return $conteudo_html;
    }

    /**
     * Verifica se o orcamento tem algum cronograma cadastrado.
     * @param type $id_orc
     * @return boolean
     */
    public function temCronograma($id_orc) {
        $query = "SELECT * FROM cronograma AS a WHERE a.id_orc = $id_orc";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Cadastra o cronograma.
     * @param array $array_request
     * @return boolean
     */
    public function cadastrar($array_request) {
        $porcentagem = "";
        $porcentagens = "";

        $query = "INSERT INTO cronograma (id_orc, id_cabecalho, quantidade_cabecalho, data_cadastro) "
                . "VALUES (" . $array_request["id_orc"] . ", "
                . $array_request["id_cab"] . ", "
                . $array_request["qtd"] . ", "
                . "NOW())";
        if ($this->conexao->executaQuery($query)) {
            $cronograma = $this->get($array_request["id_orc"]);

            $query2 = "SELECT * FROM orc_titulo AS a WHERE a.id_orc = " . $array_request["id_orc"] . " ORDER BY a.num_item";
            $resultado2 = $this->conexao->executaQuery($query2);
            while ($titulo = $this->conexao->getRegistros($resultado2)) {

                for ($i = 1; $i <= $array_request["qtd"]; $i++) {
                    $porcentagem = filter_input(INPUT_POST, $titulo["id"] . "_" . $i);
                    if (empty($porcentagem)) {
                        $porcentagem = "0,00";
                    }
                    $porcentagens .= $porcentagem . ";";
                }

                $query3 = "INSERT INTO cronograma_titulo (id_cronograma, id_titulo, porcentagens) "
                        . "VALUES (" . $cronograma->getId() . ", "
                        . $titulo["id"] . ", "
                        . "'$porcentagens')";
                $this->conexao->executaQuery($query3);
                $porcentagens = "";
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Edita o cronograma.
     * @param array $array_request
     * @return boolean
     */
    public function editar($array_request) {
        $porcentagem = "";
        $porcentagens = "";
        $qtdEditado = 0;

        $query = "SELECT * FROM orc_titulo AS a WHERE a.id_orc = " . $array_request["id_orc"] . " ORDER BY a.num_item";
        $resultado = $this->conexao->executaQuery($query);
        $qtdTitulos = $this->conexao->getQtdRegistros($resultado); 
        while ($titulo = $this->conexao->getRegistros($resultado)) {

            for ($i = 1; $i <= $array_request["qtd"]; $i++) {
                $porcentagem = filter_input(INPUT_POST, $titulo["id"] . "_" . $i);
                if (empty($porcentagem)) {
                    $porcentagem = "0,00";
                }
                $porcentagens .= $porcentagem . ";";
            }

            $query2 = "UPDATE cronograma_titulo SET porcentagens = '$porcentagens' WHERE id_titulo = " . $titulo["id"];
            if ($this->conexao->executaQuery($query2)) {
                $qtdEditado++;
            }

            $porcentagens = "";
        }
        
        if ($qtdEditado == $qtdTitulos) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Retorna um registro (objeto) de 'cronograma'
     * @param inteiro $id_orc
     * @return \Cronograma
     */
    public function get($id_orc) {
        $orcamento = new Orcamento();
        $cronograma = new Cronograma();
        $cabecalho = new CronogramaCabecalho();

        $query = "SELECT a.*, DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS dtCadastro FROM cronograma AS a WHERE a.id_orc = $id_orc";
        $resultado = $this->conexao->executaQuery($query);
        $array_cronograma = $this->conexao->getRegistros($resultado);

        $orcamento->setId($array_cronograma["id_orc"]);
        $cabecalho->setId($array_cronograma["id_cabecalho"]);

        $cronograma->setId($array_cronograma["id"]);
        $cronograma->setOrcamento($orcamento);
        $cronograma->setCabecalho($cabecalho);
        $cronograma->setQuantidadeCabecalho($array_cronograma["quantidade_cabecalho"]);
        $cronograma->setDataCadastro($array_cronograma["dtCadastro"]);

        return $cronograma;
    }

    public function getPorcentagens($id_titulo) {
        $query = "SELECT a.porcentagens FROM cronograma_titulo AS a WHERE a.id_titulo = $id_titulo";
        $resultado = $this->conexao->executaQuery($query);
        $registro = $this->conexao->getRegistros($resultado);
        return explode(';', $registro["porcentagens"]);
    }

}
?>

