<?

class ModuloSubmodulo {

    private $id;
    private $modulo;
    private $submodulo;

    public function ModuloSubmodulo() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getModulo() {
        return $this->modulo;
    }

    public function getSubmodulo() {
        return $this->submodulo;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setModulo($modulo) {
        $this->modulo = $modulo;
    }

    public function setSubmodulo($submodulo) {
        $this->submodulo = $submodulo;
    }

}

?>
