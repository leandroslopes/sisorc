<?

class Subtitulo {

    private $id;

    /**
     *
     * @var Titulo 
     */
    private $titulo;
    private $subtitulo;
    private $numeroItem;

    public function Subtitulo() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getSubtitulo() {
        return $this->subtitulo;
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

    public function setSubtitulo($subtitulo) {
        $this->subtitulo = $subtitulo;
    }

    public function setNumeroItem($numeroItem) {
        $this->numeroItem = $numeroItem;
    }

}
?>

