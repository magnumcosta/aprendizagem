<!DOCTYPE html>
<?php
session_start(); 
require './_app/Config.inc.php';
?>

<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>admin</title>
        <!--<link rel="stylesheet" href="css/style.css">-->
        <link rel="stylesheet" href="css/estilo.css">
        <link rel="stylesheet" href="lib/bootstrap/bootstrap.min.css" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,800' rel='stylesheet' type='text/css'>

    </head>
    <body>            
                
              <img src="img/dev3.jpg">
                

                <form class="box" action="" method="post">
                    
                    <h1><b>LOGIN</b></h1> 

                    <?php // procedimento responsável em executar o acesso ao painel ou recusá-lo  caso o este acesso seja restrito           
                    $sessao = new sessao(3); //classe que auxilia a iniciar o acesso ao sistema            
                    if ($sessao->CheckLogin()):
                        header('location:painel.php'); 
                    endif;
                    $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                    //var_dump($hostname);
                    
                    $datalogin = filter_input_array(INPUT_POST,FILTER_DEFAULT);

                    if ($datalogin['AdminLogin']):                            
                        $sessao->exeAcesso($datalogin);
                    endif;

                    if (!$sessao->getResult()):
                        MGErro($sessao->getError()[0], $sessao->getError()[1]);
                    else:
                        MGErro($sessao->getError()[0], $sessao->getError()[1]);    
                        header('location:painel.php'); 
                    endif;
                    // essa instrução não permite o acesso direto ao painel, ou seja, caso tente-se acesso direto ao painel e não exista sessão e o joga para fora do sistema
                    $get = filter_input(INPUT_GET,'exe', FILTER_DEFAULT);

                    if (!empty($get)):
                        if ($get == 'restrito'):
                            MGErro('Acesso Negado, entre com usuário e senha para ter acesso ao painel de controle', MG_ERROR);
                        elseif ($get == 'logoff'):
                            MGErro('Sessão encerrada com sucesso.', MG_ACCEPT);                               
                        endif;            
                    endif;
                    ?>

                                 
                            <input type="email" name="user" class="form-control" placeholder="e-mail" autofocus/>                             
                                      
                            <input type="password" name="pass" class="form-control" placeholder="senha"/> 
                      
                        
                            <input type="checkbox" value="remember-me">
                            <label>Lembre-me</label>
                        
                    
                    <div>
                            <button class="btn btn-sm   btn-primary" name="AdminLogin" value="Logar" type="submit"> ENVIAR </button><br>
                    </div> 
         
        <script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="lib/js/bootstrap.min.js"></script>
    </body>
</html>
