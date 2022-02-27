<?

class Cargo {

    private $id;
    private $nome;
    private $usuarioCadastro;
    private $dataCadastro;

    public function __construct() {
        ;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getUsuarioCadastro() {
        return $this->usuarioCadastro;
    }

    public function setUsuarioCadastro($usuarioCadastro) {
        $this->usuarioCadastro = $usuarioCadastro;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

}

?>
