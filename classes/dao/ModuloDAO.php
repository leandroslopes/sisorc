<?

include_once($_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";

class ModuloDAO {

    private $conexao;
    private $funcionario;

    public function ModuloDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Mostra os módulos que o funcionário tem acesso.
     * @param array $funcionario
     * @param boolean $is_modulo
     * @return string
     */
    public function getModulos($funcionario, $is_modulo) {
        $conteudo_html = "";
        $diretorio = "modulos/";
        
        if ($is_modulo) {
            $diretorio = "../";
        }

        $query = "SELECT a.id, a.nome, a.diretorio, a.icone FROM modulo AS a, mod_acesso AS b 
                  WHERE a.id = b.id_mod AND b.id_setor = " . $funcionario["id_setor"] . " ORDER BY a.id";
        $resultado = $this->conexao->executaQuery($query);
        $conteudo_html .= "<ul>";
        while ($modulo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<li>";
            $conteudo_html .= "<a class='textoPequeno' href='" . $diretorio . $modulo["diretorio"] . "/index.php?id=" . $modulo["id"] . "'>";
            $conteudo_html .= "<div id='" . $modulo["icone"] . "'></div> " . $modulo["nome"];
            $conteudo_html .= "</a>";
            $conteudo_html .= "</li>";
        }
        $conteudo_html .= "</ul>";
        return utf8_encode($conteudo_html);
    }

    /**
     * Retorna um módulo.
     * @param inteiro $id_modulo
     * @return array
     */
    public function getModulo($id_modulo) {
        $query = "SELECT * FROM modulo AS a WHERE a.id = $id_modulo";
        $resultado = $this->conexao->executaQuery($query);
        return $this->conexao->getRegistros($resultado);
    }

    /**
     * Lista os módulos.
     * @return string
     */
    public function listarModulos($id_modulo) {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>M&Oacute;DULO</th>";
        $conteudo_html_topo .= "<th>DIRET&Oacute;RIO</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.id, a.nome, a.diretorio FROM modulo AS a ORDER BY a.nome";
        $resultado = $this->conexao->executaQuery($query);
        while ($modulo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td><a href='modulo.php?id=" . $id_modulo . "&id_modulo_cadastrado=" . $modulo["id"] . "'>" . utf8_encode($modulo["nome"]) . "</a></td>";
            $conteudo_html .= "<td>" . $modulo["diretorio"] . "</td>";
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
     * Lista os submódulos de um módulo.
     * @param inteiro $id_modulo
     * @return string
     */
    public function listarSubmodulos($id_modulo) {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>ID</th>";
        $conteudo_html_topo .= "<th>SUBM&Oacute;DULO</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT b.id, b.nome FROM mod_submodulo AS a, submodulo AS b WHERE a.id_submod = b.id AND a.id_mod = $id_modulo ORDER BY b.nome";
        $resultado = $this->conexao->executaQuery($query);
        while ($submodulo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . $submodulo["id"] . "</td>";
            $conteudo_html .= "<td>" . utf8_encode($submodulo["nome"]) . "</td>";
            $conteudo_html .= "</tr>";
        }
        $conteudo_html .= "</tbody>";

        $conteudo_html_rodape .= "</table>";

        return $conteudo_html_topo . $conteudo_html . $conteudo_html_rodape;
    }

    /**
     * Lista os setores que tem acesso a um determinado módulo.
     * @param inteiro $id_modulo
     * @return string
     */
    public function listarSetores($id_modulo) {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>SETOR</th>";
        $conteudo_html_topo .= "<th>EXCLUIR</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.id AS idAcessoSetor, b.id, b.nome FROM mod_acesso AS a, setor AS b WHERE a.id_setor = b.id AND a.id_mod = $id_modulo ORDER BY b.nome";
        $resultado = $this->conexao->executaQuery($query);
        while ($setor = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . utf8_encode($setor["nome"]) . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<input type='hidden' name='idAcessoSetor' id='idAcessoSetor' value='" . $setor["idAcessoSetor"] . "'/>";
            $conteudo_html .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirSetorAcesso tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "</tr>";
        }
        $conteudo_html .= "</tbody>";

        $conteudo_html_rodape .= "</table>";

        return $conteudo_html_topo . $conteudo_html . $conteudo_html_rodape;
    }

    /**
     * Retorna um 'select' com os setores a terem acesso ao módulo.
     * @param inteiro $id_modulo
     * @return string
     */
    public function getSelectSetorAdicionar($id_modulo) {
        $conteudo_html = "";

        $query = "SELECT a.* FROM setor AS a WHERE a.id != ALL (SELECT b.id_setor FROM mod_acesso AS b WHERE b.id_mod = $id_modulo)
                  ORDER BY a.nome";
        $resultado = $this->conexao->executaQuery($query);

        $conteudo_html .= "<select name='setor'>";
        while ($setor = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<option value='" . $id_modulo . "_" . $setor["id"] . "'>" . $setor["nome"] . "</option>";
        }
        $conteudo_html .= "</select>";

        return $conteudo_html;
    }

    /**
     * Adiciona o acesso do setor a um módulo.
     * @param inteiro $id_modulo
     * @param inteiro $id_setor
     * @return boolean
     */
    public function adicionarAcessoSetor($id_modulo, $id_setor) {
        $query = "INSERT INTO mod_acesso (id_mod, id_setor, usuario_cadastro, data_cadastro) 
                  VALUES ($id_modulo, $id_setor, " . $this->funcionario->getCodigo() . ", NOW())";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Exclui o acesso de um setor ao módulo.
     * @param inteiro $id_acesso_setor
     * @return boolean
     */
    public function excluirAcessoSetor($id_acesso_setor) {
        $query = "DELETE FROM mod_acesso WHERE id = $id_acesso_setor";
        return $this->conexao->executaQuery($query);
    }

}

?>
