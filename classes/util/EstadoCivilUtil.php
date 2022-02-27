<?

class EstadoCivilUtil {

    const SOLTEIRO = 1;
    const CASADO = 2;
    const VIUVO = 3;

    /**
     * Metodo construtor.
     */
    public function EstadoCivilUtil() {
        
    }

    /**
     * Retorna o nome estado civil.
     * @param inteiro $id
     * @return string
     */
    public static function getNomeEstadoCivil($id) {
        switch ($id) {
            case self::SOLTEIRO:
                return "Solteiro(a)";
                break;
            case self::CASADO:
                return "Casado(a)";
                break;
            case self::VIUVO:
                return "Viúvo(a)";
                break;
        }
    }

    /**
     * Retorna um 'select' com os estados civis.
     * @param inteiro $id
     * @return string
     */
    public static function getSelect($id) {
        $conteudo_html = "";
        $conteudo_html .= "<select name='est_civil' id='est_civil'>";

        $casado = "";
        $viuvo = "";
        $solteiro = 'selected';        
        if ($id == self::CASADO) {
            $casado = 'selected';
        } elseif ($id == self::VIUVO) {
            $viuvo = 'selected';
        }            

        $conteudo_html .= "<option value='" . self::SOLTEIRO . "' $solteiro>" . self::getNomeEstadoCivil(self::SOLTEIRO) . "</option>";
        $conteudo_html .= "<option value='" . self::CASADO . "' $casado>" . self::getNomeEstadoCivil(self::CASADO) . "</option>";
        $conteudo_html .= "<option value='" . self::VIUVO . "' $viuvo>" . self::getNomeEstadoCivil(self::VIUVO) . "</option>";

        $conteudo_html .= "</select>";
        return $conteudo_html;
    }
}

?>
