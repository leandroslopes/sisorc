<?

class TituloComposicao {

    private $id;

    /**
     *
     * @var Titulo
     */
    private $titulo;

    /**
     *
     * @var OrcComposicao 
     */
    private $composicao;
    private $numeroItem;

    public function TituloComposicao() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getComposicao() {
        return $this->composicao;
    }

    public function getNumeroItem() {
        return $this->numeroItem;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitulo(Titulo $titulo) {
        $this->titulo = $titulo;
    }

    public function setComposicao(OrcComposicao $composicao) {
        $this->composicao = $composicao;
    }

    public function setNumeroItem($numeroItem) {
        $this->numeroItem = $numeroItem;
    }

}
?>

