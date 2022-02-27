<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Pessoa.php";

class Funcionario extends Pessoa {

    const ATIVO = 0;
    const INATIVO = 1;

    private $id;
    private $senha;

    /**
     *
     * @var Cargo 
     */
    private $cargo;

    /**
     *
     * @var Setor
     */
    private $setor;
    private $situacao;

    public function __construct() {
        parent::__construct();
    }

    public function getNomeSituacao($situacao) {
        switch ($situacao) {
            case self::ATIVO:
                return "ATIVO";
                break;
            case self::INATIVO:
                return "INATIVO";
                break;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getCargo() {
        return $this->cargo;
    }

    public function getSetor() {
        return $this->setor;
    }

    public function getSituacao() {
        return $this->situacao;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    public function setSetor($setor) {
        $this->setor = $setor;
    }

    public function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    /**
     * Verifica se o usuario estah logado.
     */
    public static function estaLogado() {
        if (isset($_SESSION["funcionario"])) {
            return 1;
        } else {
            header("Location: sair.php");
        }
    }

}

?>
