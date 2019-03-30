<?php

/**
 *  Check.class [Helper-Auxiliar]
 *  Classe responsavel por validar e manipular dados do sistema
 * @copyright (c) 2018, Magnum Treinamento ltda
 */
class Check {

    private static $Data;
    private static $Format;

    /*
     * ***************************************
     * **********  PUBLIC METHODS  **********
     * ***************************************
     */

    /** Método que:
     * <b>Tranforma URL:</b> Tranforma uma string no formato de URL amigável e retorna o a string convertida!
     * @param STRING $Name = Uma string qualquer
     * @return STRING = $Data = Uma URL amigável válida
     */
    public static function Email($Email) {

        self::$Data = (String) $Email;
        self::$Format = "/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/";

        if (preg_match(self::$Format, self::$Data)):

            return true;
        else:
            return false;

        endif;
    }

    /** Método que:
     * <b>Tranforma URL:</b> Tranforma uma string no formato de URL amigável e retorna o a string convertida!
     * @param STRING $Name = Uma string qualquer
     * @return STRING = $Data = Uma URL amigável válida
     */
    public static function Name($Name) {

        self::$Format = [];
        self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

        // 1. Passo: Usa-se a função nativa do php strtr (string translate)
        // Obs. Como a String obtida no parametro $Name poderá vir com caracter especial devemos usar a função nativa UTF8_decode

        self::$Data = strtr(utf8_decode($Name), utf8_decode(self::$Format['a']), self::$Format['b']);

        //2. passo: Eliminar espaços e formatação html

        self::$Data = strip_tags(trim(self::$Data));

        //3. passo: Substituir espaço por data

        self::$Data = str_replace(' ', '-', self::$Data);
        self::$Data = str_replace(array('--', '---', '----'), '-', self::$Data);


        //4. passo: Retornar formatando a escrita 

        return strtolower(utf8_encode(self::$Data));
    }

    /** Método que:
     * <b>Tranforma Data:</b> Transforma uma data no formato DD/MM/YY em uma data no formato TIMESTAMP!
     * @param STRING $Name = Data em (d/m/Y) ou (d/m/Y H:i:s)
     * @return STRING = $Data = Data no formato timestamp!
     */
    public static function Date($Data) {

        self::$Format = explode(' ', $Data);
        self::$Data = explode('/', self::$Format['0']);

        if (empty(self::$Format[1])):
            self::$Format['1'] = date('H:i:s');
        endif;

        self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0] . ' ' . self::$Format['1'];

        return self::$Data;
    }

    /** Método que:
     * <b>Limita os Palavras:</b> Limita a quantidade de palavras a serem exibidas em uma string!
     * @param STRING $String = Uma string qualquer
     * @return INT = $Limite = String limitada pelo $Limite
     * @return = $Pointer Vai servir para alterar o final da String
     */
    public static function Words($String, $Limite, $Pointer = Null) {

        self::$Data = strip_tags(trim($String));
        self::$Format = (Int) $Limite;

        $ArrWords = explode(' ', self::$Data);
        $NumWords = count($ArrWords);
        $NewWords = implode(' ', array_slice($ArrWords, 0, self::$Format));

        $Pointer = (empty($Pointer) ? '...' : '  ' . $Pointer );

        $Result = (self::$Format < $NumWords ? $NewWords . $Pointer : self::$Data);
        return $Result;

        //var_dump($ArrWords,$NumWords,$NewWords);
    }

    /**
     * <b>Obter categoria:</b> Informe o name (url) de uma categoria para obter o ID da mesma.
     * @param STRING $category_name = URL da categoria
     * @return INT $category_id = id da categoria informada
     */
    public static function CatByName($CategoryName) {


        $Leitura = new Leitura;
        $Leitura->ExeRead('ws_categories', "WHERE category_name = :name", "name={$CategoryName}");
            if ($Leitura->getRowCount()):

            return $Leitura->getResult()[0]['category_id'];

        else :
          
            echo "A categoria {$CategoryName} não foi encontrada!";
            die;

        endif;
    }
    
    public static function UserOnLine() {
        
        $agora= date( 'Y-m-d H:i:s'  );
        
        $DeleteUserOnLine = new Delete;
        
        $DeleteUserOnLine->ExeDelete('ws_siteviews_online', 'WHERE online_endview <:agora', "agora={$agora}");
        
        $ReadUserOnLine = new Leitura;
        
        $ReadUserOnLine->ExeRead('ws_siteviews_online');
        
        return $ReadUserOnLine->getRowCount();
        
    }

}
