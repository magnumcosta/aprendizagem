<?php

/**
 *  Create.class [TIPO]
 *  Classe responsável por cadastro genéricos no Banco de Dados
 * @copyright (c) 2018, Magnum Treinamento ltda
 */
class Createe extends Conn {

    Private $Tabela;
    Private $Dados;
    Private $Result;

    /** @var PDOStatementens */
    Private $Create;

    /** @var PDO */
    Private $Conn;

    /**
     * <b>ExeCreate:<b> Executa um cadastro no BD utilizando o prepared statement
     * @param String $Tabela = Informe o nome da tabela no banco
     * @param Array $Dados = informe um array atribuitivo
     */
    public function ExeCreate($Tabela, $Dados) {

        $this->Tabela = (string) $Tabela;
        $this->Dados = (array) $Dados;
        $this->getSyntax();
        $this->Execute();
    }

    public function getResult() {

        return $this->Result;
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
        $this->Conn = parent::getConn(); // aqui o atributo private $Conn recebe a conexão

        $this->Create = $this->Conn->prepare($this->Create);
    }

    private function getSyntax() {

        $Campo = implode(',', array_keys($this->Dados)); // $Fileds é nome original da variável
        $Valores = ":" . implode(', :', array_keys($this->Dados));  // $Places é nome original da variável

        $this->Create = "INSERT INTO {$this->Tabela} ({$Campo}) VALUES ({$Valores})";
    }

    private function Execute() {

        $this->Connect();

        try {

            $this->Create->execute($this->Dados);

            $this->Result = $this->Conn->lastInsertId();
        } catch (PDOException $e) {

            $this->Result = Null;

            WSErro("<b>Erro ao cadastrar:</b> {$e->getMessage()}", $e->getCode());
        }
    }

}
