<?

class SexoUtil {

    const MASCULINO = 1;
    const FEMININO = 2;

    /**
     * Metodo construtor.
     */
    public function SexoUtil() {
        
    }

    /**
     * Retorna o nome do sexo.
     * @param inteiro $id
     * @return string
     */
    public static function getNomeSexo($id) {
        switch ($id) {
            case self::MASCULINO:
                return "Masculino";
                break;
            case self::FEMININO:
                return "Feminino";
                break;
        }
    }

    /**
     * Retorna um 'select' com os sexos.
     * @param inteiro $id
     * @return string
     */
    public static function getSelect($id) {
        $conteudo_html = "";
        $conteudo_html .= "<select name='sexo' id='setor'>";

        $feminino = "";
        $masculino = 'selected';        
        if ($id == self::FEMININO) {
            $feminino = 'selected';
        }

        $conteudo_html .= "<option value='" . self::MASCULINO . "' $masculino>" . self::getNomeSexo(self::MASCULINO) . "</option>";
        $conteudo_html .= "<option value='" . self::FEMININO . "' $feminino>" . self::getNomeSexo(self::FEMININO) . "</option>";

        $conteudo_html .= "</select>";
        return $conteudo_html;
    }

}

?>
