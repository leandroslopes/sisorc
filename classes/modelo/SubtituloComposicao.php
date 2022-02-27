<?

class SubtituloComposicao {

    private $id;

    /**
     *
     * @var Subtitulo
     */
    private $subtitulo;

    /**
     *
     * @var OrcComposicao 
     */
    private $composicao;
    private $numeroItem;

    public function SubtituloComposicao() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getSubtitulo() {
        return $this->subtitulo;
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

    public function setSubtitulo(Subtitulo $subtitulo) {
        $this->subtitulo = $subtitulo;
    }

    public function setComposicao(OrcComposicao $composicao) {
        $this->composicao = $composicao;
    }

    public function setNumeroItem($numeroItem) {
        $this->numeroItem = $numeroItem;
    }

}
?>

