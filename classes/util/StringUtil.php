<?

class StringUtil {

    /**
     * Metodo construtor.
     */
    public function StringUtil() {
        
    }

    /**
     * Divide uma string para um array.
     * @param string $delimitador
     * @param string $string
     * @return array
     */
    public static function getArrayStrings($delimitador, $string) {
        return explode($delimitador, $string);
    }

    /**
     * Remove espacos do inicio e do fim de uma string.
     * @param string $string
     * @return string
     */
    public static function removeEspacos($string) {
        return $string = trim($string);
    }

}

?>
