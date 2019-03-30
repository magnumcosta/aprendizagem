<?php

/**
 *  Login.class [MODEL]
 *  Respoansável por autenticar, validar do sistema do login.
 *  @copyright (c) 2018, Magnum Treinamento ltda
 */
class Login {
    
    private $level;
    private $Email;
    private $Senha;
    private $Error;
    private $Result;
    
    function __construct($level) {
        $this->level = (int) $level;
    }
    
    public function ExeLogin(array $UserLogin) {
        
        $this->Email = $UserLogin['user'];
        $this->Senha = $UserLogin['pass'];
        $this->SetLogin();
    }

// Métodos Privados
    
    private function SetLogin() {
        
        if (!$this->Email || !$this->Senha || !check::Email($this->Email)):
            
            $this->Error = ['Informe seu e-mail e/ou senha',WS_ALERT];
        
        elseif (!$this->GetUser()):
            
            $this->Error = ['E-mail e/ou senha Invalidos',WS_ALERT];
            
        endif;
        
    }
    
    private function GetUser() {
        
        $read = new Leitura;
        $read->ExeRead("ws_users", "WHERE user_email= :e AND user_password= :p", "e={$this->Email}&p={$this->Senha}");
        
        if($read->getResult()):

            $this->Error = $read->getResult()[0];
            return true;
        
        else:
            
            return false;
            
        endif;
        
    }
       
    
    
}