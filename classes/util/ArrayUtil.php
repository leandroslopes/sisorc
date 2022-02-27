<?

class ArrayUtil {

    public function ArrayUtil() {
        
    }

    /**
     * Verifica se uma chave existe num array. Caso a chave
     * exista, ela retornar o valor da chave. Caso a chave,
     * não exista, retornar uma string vazia.
     *
     * @param $chave chave do array
     * @param $array o array que será verificado
     * @return mixed
     */
    public static function array_get($chave, $array) {

        if (isset($array[$chave])) {
            return $array[$chave];
        } else {
            return '';
        }
    }

}
?>

