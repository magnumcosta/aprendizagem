<?php

namespace MagnumAss\Aprendizagem\Model;

/**
 *  lllogin.class [TIPO]
 *  descricao
 * @copyright (c) year, Magnum Treinamento ltda
 */
class lllogin {

    private $Level;
    private $Email;
    private $Senha;
    private $Error;
    private $Result;

    function __construct($Level) {
        $this->Level = (int) $Level;
    }

    function getError() {
        return $this->Error;
    }

    function getResult() {
        return $this->Result;
    }

    public function ExeLogin(array $UserLogin) {

        $this->Email = strip_tags(trim($UserLogin['user']));
        $this->Senha = strip_tags(trim($UserLogin['pass']));
        $this->SetLogin();
    }

    public function CheckLogin() {

        if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['user_level'] < $this->Level):

            unset($_SESSION['userlogin']);
            return false;

        else:

            return true;

        endif;
    }

    private function SetLogin() {

        if (!$this->Email || !$this->Senha || !check::Email($this->Email)):

            $this->Error = ['por favor, insira email e/ou senha', WS_ALERT];
            $this->Result = false;
            unset($_SESSION['userlogin']);
            return false;

        Elseif (!$this->GetUser()):

            $this->Error = ['e-mail ou senha invalido, por favor tente novamente', WS_ALERT];
            $this->Result = false;



        elseif ($this->Result['user_level'] < $this->Level):

            $this->Error = ["Desculpe, {$this->Result['user_name']}, mas você não tem permissão da administrador para acessar o sistema.", WS_ERROR];
            $this->Result = false;
            unset($_SESSION['userlogin']);
            return false;

        else:

            // echo 'Logar aqui';
            // die;
            $this->Execute();


        endif;
    }

    private function GetUser() {

        $this->Senha = md5($this->Senha);

        $read = new Leitura;

        $read->ExeRead("ws_users", "WHERE user_email= :e AND user_password= :p", "e={$this->Email}&p={$this->Senha}");

        if ($read->getResult()):

            $this->Result = $read->getResult()[0];
            return true;

        else:

            return false;

        endif;
    }

    private function Execute() {

        if (!session_id()):
            session_start();
        endif;

        $_SESSION['userlogin'] = $this->Result;

        $this->Error = ["Olá, {$this->Result['user_name']}, seja bem vindo e aguarde o redirecionamento", WS_ACCEPT];

        $this->Result = true;
    }

}
