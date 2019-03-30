<?php

/**
 *  sessao.class [TIPO]
 *  descricao
 * @copyright (c) year, Magnum Treinamento ltda
 */
class sessao {

    private $User;
    private $Pass;
    private $Nivel;
    private $Error;
    private $Result;
    
    
    
    function __construct($nivel) {
        $this->Nivel = $nivel;
    }

    
    public function exeAcesso(array $UserPost) {
        $this->User = strip_tags(trim($UserPost['user']));
        $this->Pass = strip_tags(trim($UserPost['pass']));
        $this->SetSessao();
    }
    
    function getError() {
        return $this->Error;
    }

    function getResult() {
        return $this->Result;
    }
    
    public function CheckLogin() {
        
        if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['user_level']<$this->Nivel):
            unset($_SESSION['userlogin']);
            return False;
        else:
            return true;
        
        endif;
    }

    
    private function setSessao() {
        
        if (empty($this->User) || empty($this->Pass) || !check::Email($this->User)):
                $this->Error = ['preencha o campo de e-mail ou senha.',MG_INFOR];       
                $this->Result = FALSE;
        elseif (!$this->getSessao()):
                 $this->Error = ['Usuário não cadastrado ou senha invalida, por favor tente novamente.',MG_ALERT];       
                 $this->Result = FALSE; 
        elseif ($this->Result['user_level'] < $this->Nivel):
            $this->Error = ["Desculpe {$this->Result['user_name']}, mas esta é uma área restrita, consulte o administrador do sistema para maiores informações.", MG_ERROR];                
            $this->Result = False;
        else:
            $this->ExecuteLogin();
            
        endif;
    }


    private function getSessao() {
        $this->Pass = md5($this->Pass);
        $leitura = New Leitura;
        $leitura->ExeRead('ws_users', "WHERE user_email=:email AND user_password=:pass", "email={$this->User}&pass={$this->Pass}");
        
        if($leitura->getResult()):            
            $this->Result = $leitura->getResult()[0];           
            return true;
        else:
            return false;
        endif; 
     }
    
    private function executeLogin() {
        
        if (!session_id()):
            session_start();
        else:
            $_SESSION['userlogin'] = $this->Result;
            $this->Error = ["Olá {$this->Result['user_name']}, por favor, aguarde o redirecionamento.", MG_ACCEPT];
            $this->Result  = TRUE;
            
        endif;
        
    }
    
    
            
     
    
    
    
    
    
    
}
