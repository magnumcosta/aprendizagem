<?php

/**
 *  Login.class [MODEL]
 *  Respoansável por autenticar, validar do sistema do login.
 *  @copyright (c) 2018, Magnum Treinamento ltda
 */
class Login {
    
    
    private $Level;
    private $Email;
    private $Senha;
    private $Error;
    private $Result;
        
    function __construct($Level) {
        $this->Level = (int)$Level;
    }
    

    public function ExeLogin (array $UserData) {

        $this->Email = (string) strip_tags(trim($UserData['user']));
        $this->Senha = (string) strip_tags(trim($UserData['pass']));
        $this->setLogin();
    }
    
      function getResult() {
        return $this->Result;
    }
    
    function getError() {
        return $this->Error;
    }
        
    public function CheckLogin() {
        
        if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['user_level']<$this->Level):
            unset($_SESSION['userlogin']);
            return False;
        else:
            return true;
        
        endif;
    }


    // MÉTODOS PRIVADOS
    
    private function setLogin() {
        
        if (!$this->Email || !$this->Senha || !check::Email($this->Email)):            
            $this->Error = ['Informe seu email e senha, por favor.', WS_INFOR];
            $this->Result = False;
            
        elseif(!$this->getUser()):               
            $this->Error = ['E-mail invalido e/ou senha incorreta, por favor repita a operação', WS_ALERT];
            $this->Result = False;
        elseif ($this->Result['user_level'] < $this->Level):
            $this->Error = ["Desculpe {$this->Result['user_name']}, mas esta é uma área restrita, consulte o administrador do sistema para maiores informações.", WS_ERROR];                
            $this->Result = False;
        else:
            $this->Execute();           
        endif;
        
                
    }
    //Obter usuário no bando de dados
    private function getUser() {
        $this->Senha = md5($this->Senha); //método para criptografar a senha
        
        $Read = new Leitura;
        $Read->ExeRead('ws_users','WHERE user_email =:e AND user_password=:p', "e={$this->Email}&p={$this->Senha}");
        
        if($Read->getResult()):            
            $this->Result = $Read->getResult()[0];
            return true;
        else:
            return false;
        endif;        
    }
    
    private function Execute() {
        
        if(!session_id()):
            session_start();
        endif;        
        $_SESSION['userlogin']= $this->Result;
        $this->Error = ["Olá {$this->Result['user_name']}, por favor, aguarde o redirecionamento.", WS_ACCEPT];
        $this->Result  = TRUE;
        
    }
}