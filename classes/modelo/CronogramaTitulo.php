<?

class CronogramaTitulo {

    private $id;

    /**
     *
     * @var Cronograma
     */
    private $cronograma;

    /**
     *
     * @var Titulo
     */
    private $titulo;
    private $porcentagens;

    public function CronogramaTitulo() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getCronograma() {
        return $this->cronograma;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getPorcentagens() {
        return $this->porcentagens;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setCronograma(Cronograma $cronograma) {
        $this->cronograma = $cronograma;
    }

    public function setTitulo(Titulo $titulo) {
        $this->titulo = $titulo;
    }

    public function setPorcentagens($porcentagens) {
        $this->porcentagens = $porcentagens;
    }

    public function getValorMes($porcentagem, $total) {
        return (($porcentagem * $total) / 100);
    }

}

?>