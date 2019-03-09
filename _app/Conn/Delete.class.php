<?php

/**
 *  Update.class [TIPO]
 *  Classe responsável por exclusões genéricas no Banco de Dados
 * @copyright (c) 2018, Magnum Treinamento ltda
 */
class Delete extends Conn {

    Private $Tabela; //atributo de seleção
    Private $Termos; //atributo de seleção
    Private $Places; //atributo para fazer as BindValues
    Private $Result;

    /** @var PDOStatementens */
    Private $Delete; //Este atributo sempre terá o nome da classe

    /** @var PDO */
    Private $Conn;

    /**
     * <b>execução de método facilitador<b> Executa uma leitura no BD utilizando o prepared statement
     *     
     */
    public function ExeDelete($Tabela, $Termos, $ParseString) {
        
        $this->Tabela = (String) $Tabela;
        $this->Termos = (String) $Termos;
        
        parse_str($ParseString, $this->Places);
        $this->getSyntax();
        $this->Execute();

        
    }

    public function getResult() {
        return $this->Result;
    }

    /* Método que retorna a quantidade de de itens trazidos pelo resultado */

    public function getRowCount() {
        return $this->Delete->Rowcount();
    }

    /* Método para obter nossos stored procedures, ou seja, nossos ParseString */

    public function setPlaces($ParseString) {
        parse_str($ParseString, $this->Places);
        $this->getSyntax();
        $this->Execute();
    }

    /**
     * ****************************************
     * ********* PRIVATE METHODS **************
     * ****************************************
     */

    /**
     * Método responsável por fazer a conexão com o Banco de Dado via PDO utilizando os métodos da classe pai 'Conn'
     */
    private function Connect() {
        
        $this->Conn = parent::getConn();
        $this->Delete  = $this->Conn->prepare($this->Delete); 
        
        
    }

    private function getSyntax() {
        
        $this->Delete = "DELETE FROM {$this->Tabela} {$this->Termos}";       
        
    }

       

    private function Execute() {
        
        $this->Connect();

        try {
            $this->Delete->execute($this->Places);
            $this->Result = True;
            
        } catch (PDOException $e) {
          
            $this->Result = Null;

            WSErro("<b>Erro ao Deletar:</b> {$e->getMessage()}", $e->getCode());
        }
    }

}
