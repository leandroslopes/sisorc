<?

include_once($_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";

class SubmoduloDAO {

    private $conexao;
    private $funcionario;

    public function SubmoduloDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();
        
        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Mostra os submódulos de um módulo para serem acessados.
     * @param array $funcionario
     * @param inteiro $id_modulo
     * @return string
     */
    public function mostrarSubmodulos($funcionario, $id_modulo) {
        $conteudo_html = "";
        $query = "SELECT a.id, a.nome, a.icone, a.arquivo FROM submodulo AS a, submod_acesso AS b, mod_submodulo AS c 
                  WHERE a.id = b.id_submod AND a.id = c.id_submod AND b.id_cargo = " . $funcionario["id_cargo"]
                . " AND c.id_mod = $id_modulo";
        $resultado = $this->conexao->executaQuery($query);
        while ($submodulo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<li>";
            $conteudo_html .= "<a class='textoPequeno' href='" . $submodulo["arquivo"] . "?id=" . $id_modulo . "'>";
            $conteudo_html .= "<div id='" . $submodulo["icone"] . "'></div> " . $submodulo["nome"];
            $conteudo_html .= "</a>";
            $conteudo_html .= "</li>";
        }
        return utf8_encode($conteudo_html);
    }

    /**
     * Lista os submódulos.
     * @return string
     */
    public function listarSubmodulos($id_modulo) {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>ID</th>";
        $conteudo_html_topo .= "<th>SUBM&Oacute;DULO</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.id, a.nome FROM submodulo AS a ORDER BY a.nome";
        $resultado = $this->conexao->executaQuery($query);
        while ($submodulo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . $submodulo["id"] . "</td>";
            $conteudo_html .= "<td><a href='submodulo.php?id=" . $id_modulo . "&id_submodulo_cadastrado=" . $submodulo["id"] . "'>" . utf8_encode($submodulo["nome"]) . "</a></td>";
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
     * Lista os cargos que tem acesso a um determinado submódulo.
     * @param inteiro $id_submodulo
     * @return string
     */
    public function listarCargos($id_submodulo) {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_vazio = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>CARGO</th>";
        $conteudo_html_topo .= "<th>EXCLUIR</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.id AS idAcessoCargo, b.id, b.nome FROM submod_acesso AS a, cargo AS b 
                  WHERE a.id_cargo = b.id AND a.id_submod = $id_submodulo ORDER BY b.nome"; //echo $query;
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        while ($cargo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . $cargo["nome"] . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<input type='hidden' name='idAcessoCargo' id='idAcessoCargo' value='" . $cargo["idAcessoCargo"] . "'/>";
            $conteudo_html .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirCargoAcesso tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "</tr>";
        }

        $conteudo_html_vazio .= "<tr> <td>-</td> <td>-</td> </tr>";

        $conteudo_html_rodape .= "</tbody>";
        $conteudo_html_rodape .= "</table>";

        if ($qtdRegistros > 0)
            return $conteudo_html_topo . $conteudo_html . $conteudo_html_rodape;
        else
            return $conteudo_html_topo . $conteudo_html_vazio . $conteudo_html_rodape;
    }

    public function getSubmodulo($id_submodulo) {
        $query = "SELECT * FROM submodulo AS a WHERE a.id = $id_submodulo";
        $resultado = $this->conexao->executaQuery($query);
        return $this->conexao->getRegistros($resultado);
    }

    /**
     * Retorna um 'select' com os cargos a terem acesso ao submódulo.
     * @param inteiro $id_submodulo
     * @return string
     */
    public function getSelectCargoAdicionar($id_submodulo) {
        $conteudo_html = "";

        $query = "SELECT a.* FROM cargo AS a WHERE a.id != ALL (SELECT b.id_cargo FROM submod_acesso AS b WHERE b.id_submod = $id_submodulo)
                  ORDER BY a.nome"; //echo $query;
        $resultado = $this->conexao->executaQuery($query);

        $conteudo_html .= "<select name='cargo'>";
        while ($cargo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<option value='" . $id_submodulo . "_" . $cargo["id"] . "'>" . $cargo["nome"] . "</option>";
        }
        $conteudo_html .= "</select>";

        return $conteudo_html;
    }

    /**
     * Adiciona o acesso do cargo a um submódulo.
     * @param inteiro $id_submodulo
     * @param inteiro $id_cargo
     * @return boolean
     */
    public function adicionarAcessoCargo($id_submodulo, $id_cargo) {
        $query = "INSERT INTO submod_acesso (id_submod, id_cargo, usuario_cadastro, data_cadastro) 
                  VALUES ($id_submodulo, $id_cargo, " . $this->funcionario->getCodigo() . ", NOW())"; //echo $query;
        return $this->conexao->executaQuery($query);
    }

    /**
     * Exclui o acesso de um cargo ao submódulo.
     * @param inteiro $id_acesso_cargo
     * @return boolean
     */
    public function excluirAcessoCargo($id_acesso_cargo) {
        $query = "DELETE FROM submod_acesso WHERE id = $id_acesso_cargo";
        return $this->conexao->executaQuery($query);
    }

}

?>
