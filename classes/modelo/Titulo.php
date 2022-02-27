<?

class Titulo {

    private $id;
    /**
     *
     * @var Orcamento 
     */
    private $orcamento;
    private $titulo;
    private $numeroItem;

    public function Titulo() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getOrcamento() {
        return $this->orcamento;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getNumeroItem() {
        return $this->numeroItem;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setOrcamento($orcamento) {
        $this->orcamento = $orcamento;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setNumeroItem($numeroItem) {
        $this->numeroItem = $numeroItem;
    }

}
?>

