<?php

/**
 *  Session.class [HELPER
 * Responsavel pelas estatíticas, atualização e trafego do sistema
 * r
 *  descricao
 * @copyright (c) 2018, Magnum Treinamento ltda
 */
class Session {

    private $Date;
    private $Cache;
    private $Traffic;
    private $Browser;

    function __construct($Cache = Null) {
        session_start();
        $this->CheckSession($Cache);
    }

//Verifica e executa todos os métodos da classe
    private function CheckSession($Cache = Null) {

        $this->Date = date('Y-m-d');
        $this->Cache = ((int) $Cache ? $Cache : 20);

        if (empty($_SESSION['useronline'])):
            
            $this->SetTraffic();
            $this->SetSession();
            $this->CheckBrowser();
            $this->SetUsuario();
            $this->BrowserUpdate();

        else:
            $this->TrafficUpdate();
            $this->SessionUpdate();
            $this->CheckBrowser();  
            $this->UsuarioUpdate();

        endif;

        $this->Date = Null;
    }

//Métodos auxiliares

    /**
     * ****************************************
     * *********** SESSÃO DO USUÁRIO **********
     * ****************************************
     */

       //Inicia a sessão do usuário
    private function setSession() {
        $_SESSION['useronline'] = [
            "online_session" => session_id(),
            "online_startview" => date('Y-m-d H:i:s'),
            "online_endview" => date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes")),
            "online_ip" => filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP),
            "online_url" => filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT),
            "online_agent" => filter_input(INPUT_SERVER, "HTTP_USER_AGENT", FILTER_DEFAULT)
        ];
    }

    //Atualiza sessão do usuário!
    private function sessionUpdate() {
        $_SESSION['useronline']['online_endview'] = date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes"));
        $_SESSION['useronline']['online_url'] = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT);
    }

    /**
     * ****************************************
     * *** USUÁRIO, VISITAS E ATUALIZAÇOES ****
     * ****************************************
     */

// Verifica e insere dados na tabela
    private function SetTraffic() {
        
        $this->GetTraffic();
                
        if (!$this->Traffic):                
            $ArrSiteViews = ['siteviews_date'=> $this->Date, 'siteviews_users'=>1, 'siteviews_views'=>1,'siteviews_pages'=>1];
            $CreateSiteViews = New Createe;
            $CreateSiteViews->ExeCreate("ws_siteviews",$ArrSiteViews);            
        else:
            if(!$this->GetCookie()):                
                $ArrSiteViews = [ 'siteviews_users'=> $this->Traffic['siteviews_users']+1, 'siteviews_views'=>$this->Traffic['siteviews_views']+1,'siteviews_pages'=>$this->Traffic['siteviews_pages']+1];                    
                else:                
                $ArrSiteViews = ['siteviews_views'=>$this->Traffic['siteviews_views']+1,'siteviews_pages'=>$this->Traffic['siteviews_pages']+1];         
            endif;            
            $updateSiteWiews = New Update;
            $updateSiteWiews->ExeUpdate("ws_siteviews", $ArrSiteViews, "WHERE siteviews_date= :date", "date={$this->Date}");            
        endif;
                
    }

// Obtem dado da tabela [HELPER de traffic]

    private function GetTraffic() {

        $ReadSiteViews = new Leitura;
        $ReadSiteViews->ExeRead("ws_siteviews", "WHERE siteviews_date = :date", "date={$this->Date}");
        If ($ReadSiteViews->getRowCount()):
            
            $this->Traffic = $ReadSiteViews->getResult()[0];      
        
        endif;
    }
    // verifica e atualiza as pagewies
    
    private function TrafficUpdate() {
        
        $this->GetTraffic();
        $ArrSiteViews = [ 'siteviews_pages'=>$this->Traffic['siteviews_pages']+1];
        $UpdatePagesViews= new Update;
        $UpdatePagesViews->ExeUpdate("ws_siteviews", $ArrSiteViews, "WHERE siteviews_date= :date", "date={$this->Date}");
        
        $this->Traffic = Null;
        
    }
     
    
    // Verifica, atualiza e cria o cookie do usuário [HELPER TRAFFIC]

    private function GetCookie() {

        $Cookie = filter_input(INPUT_COOKIE, 'useronline', FILTER_DEFAULT);
        setcookie("useronline", base64_decode('UpInside'), time() + 86400);  

        if (!$Cookie):

            return FALSE;

        else:

            return TRUE;

        endif;   
        
    }

    /**
     * ****************************************
     * ***    NAVEGADORES DE USUÁRIO       ****
     * ****************************************
     */
    
    //Identifica navegador do usuário
    
    private function CheckBrowser () {

        $this->Browser = $_SESSION ['useronline']['online_agent'];

        if (strpos($this->Browser,'Chrome')):
            
            $this->Browser = 'Chrome';

        elseif (strpos($this->Browser,'Firefox')):

            $this->Browser = 'Firefox'; 

        elseif (strpos($this->Browser,'MSIE') || strpos($this->Browser,'trident/')):

            $this->Browser = 'IE';

        else:

             $this->Browser = 'Outros';

        endif;
    }
    
    //atualiza tabela com dados de navegadores

    private function BrowserUpdate() {
        
        //ws_siteviews_agent

        $readAgent = new leitura;
        $readAgent->ExeRead('ws_siteviews_agent', "WHERE agent_name = :agent", "agent={$this->Browser}");
        
        if (!$readAgent->getResult()):
            
            $ArrAgent = ['agent_name'=>$this->Browser,'agent_views'=>1];
            
            $CreateAgent = new Createe;
            $CreateAgent->ExeCreate('ws_siteviews_agent', $ArrAgent);

        else:

            $ArrAgent = ['agent_views'=> $readAgent->getResult()[0]['agent_views'] + 1];
            $updateAgent = new Update;
            
            $updateAgent->ExeUpdate ('ws_siteviews_agent', "WHERE agent_name = :agent", "agent={$this->Browser}");
            
        endif;
        

    }
    

    /**
     * ****************************************
     * ***    NAVEGADORES DE ACESSO        ****
     * ****************************************
     */

    
    
      /**
     * ****************************************
     * ********    USUÁRIOS ONLINE       ********
     * ****************************************
     */
    
    private function SetUsuario() {
        
        $sesOnline = $_SESSION['useronline'];
        $sesOnline['agent_name'] = $this->Browser;
        $userCreate = new Createe;
        $userCreate->ExeCreate("ws_siteviews_online", $sesOnline);   
        
    }
    
    private function UsuarioUpdate(){
        
        $arrOnline = [            
            'online_endview' => $_SESSION['useronline']['online_endview'],
            'online_url' => $_SESSION['useronline']['online_url']                 
        ];
        
        $userUpdate =new Update;
        $userUpdate->ExeUpdate('ws_siteviews_online', $arrOnline, ' WHERE online_session = :ses ', "ses={$_SESSION['useronline']['online_session']}");
        
        if(!$userUpdate->getRowCount()):
            
            $readSes = new Leitura;
            $readSes->ExeRead('ws_siteviews_online', ' WHERE online_session = :onSes ', "onSes={$_SESSION['useronline']['online_session']}");
            
                if (!$readSes->getRowCount()):
                    
                    $this->setSession();
                    $this->SetUsuario();
                    
                endif;
            
        endif;
        
        var_dump($arrOnline);
        
    }
    
    
}


