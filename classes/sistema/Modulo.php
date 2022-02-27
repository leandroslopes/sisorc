<?

class Modulo {

    private $id;
    private $nome;
    private $diretorio;
    private $icone;

    public function Modulo() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getDiretorio() {
        return $this->diretorio;
    }

    public function getIcone() {
        return $this->icone;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setDiretorio($diretorio) {
        $this->diretorio = $diretorio;
    }

    public function setIcone($icone) {
        $this->icone = $icone;
    }

}

?>
