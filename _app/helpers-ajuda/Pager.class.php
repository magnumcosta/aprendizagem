<?php

/**
 *  Pager.class [HELPER]
 *  Realiza a gestão e paginação dos resultados
 * @copyright (c) 2018, Parse String Treinamento ltda
 */
class Pager {

    /**  DEFINA A PÁGINA  */
    private $Page;
    private $Limit;
    private $Offset;

    /**  REALIZA A LEITURA  */
    private $Tabela;
    private $Termos;
    private $Places;

    /**  DEFINA O PAGINATOR  */
    private $Rows; //define qts linhas a gente terá para o resultado
    private $Link;
    private $MaxLinks; //define qts links serão exebidos por página
    private $First;
    private $Last;

    /**  RENDERIZA O PAGINATOR  */
    private $Paginator;

    public function __construct($Link, $First = Null, $Last = Null, $MaxLinks = Null) {
        $this->Link = (String) $Link;
        $this->First = ((String) $First ? $First : 'Primeira Página');
        $this->Last = ((String) $Last ? $Last : 'Última Página');
        $this->MaxLinks = ((int) $MaxLinks ? $MaxLinks : 5);
    }

    function getPage() {
        return $this->Page;
    }

    function getLimit() {
        return $this->Limit;
    }

    function getOffset() {
        return $this->Offset;
    }

    /*
     * ***************************************
     * **********  PUBLIC METHODS  *********
     * ***************************************
     */

    public function ExePager($Page, $Limit) {

        $this->Page = ((Int) $Page ? $Page : 1);
        $this->Limit = $Limit;
        $this->Offset = ($this->Page * $this->Limit) - $this->Limit;
    }

    public function ReturnPage() {

        if ($this->Page > 1):
            $nPage = $this->Page - 1;
            header("Location: {$this->Link}{$nPage}");
        endif;
    }

    public function ExePaginator($Tabela, $Termos = Null, $ParseString = Null) {

        $this->Tabela = (String) $Tabela;
        $this->Termos = (String) $Termos;
        $this->Places = (String) $ParseString;
        $this->GetSyntax();
    }

    function getPaginator() {
        return $this->Paginator;
    }

    /*
     * ***************************************
     * ********  PRIVATE METHODS **********
     * ***************************************
     */

    //Cria a paginação de resultados
    private function GetSyntax() {

        $Leitura = new Leitura;
        $Leitura->ExeRead($this->Tabela, $this->Termos, $this->Places);
        $this->Rows = $Leitura->getRowCount();

        if ($this->Rows > $this->Limit):

            $Paginas = ceil($this->Rows / $this->Limit);
            $MaxLinks = $this->MaxLinks;

            $this->Paginator = "<ul class=\"paginator\">";
            $this->Paginator .= "<li><a title=\"{$this->First}\" href=\"{$this->Link}1\">{$this->First}</a></li>";

            for ($ipag = $this->Page - $MaxLinks; $ipag <= $this->Page - 1; $ipag ++):

                if ($ipag > 1):
                    $this->Paginator .= "<li><a title=\"Página {$ipag}\" href=\"{$this->Link}{$ipag}\">{$ipag }</a></li>";
                endif;

            endfor;
            
            
            $this->Paginator .="<li><span class=\"active\">{$this->Page}</span></li>";


            for ($dpag = $this->Page + 1; $dpag <= $this->Page + $MaxLinks; $dpag ++):

                if ($dpag < $Paginas):
                    $this->Paginator .= "<li><a title=\"Página {$dpag}\" href=\"{$this->Link}{$dpag}\">{$dpag }</a></li>";
                endif;

            endfor;


            $this->Paginator .= "<li><a title=\"{$this->Last}\" href=\"{$this->Link}{$Paginas}\">{$this->Last}</a></li>";
            $this->Paginator .= "</ul>";
        endif;
    }

}
