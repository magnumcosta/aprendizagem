<!DOCTYPE html>
<?php

session_start(); 

require './_app/Config.inc.php';
?>

<!--
Execício de aprendizagem do curso de php
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>página de logar com bootstrap</title>
        <link rel="stylesheet" href="lib/bootstrap/bootstrap.min.css" />    
        <link rel="stylesheet" href="css/estilo.css" />
    </head>
      
    
    <body>           
        
        <div class="navigation">

            <h1>ACESSO AO SISTEMA!</h1>   

        </div>  

        <div class="formulario">
                <form class="form-signin" action="" method="post">     
                
                    
                        <?php

                        $sessao = new sessao(3);

                        if ($sessao->CheckLogin()):
                             header('location:painel.php'); 
                        endif;


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


                        $get = filter_input(INPUT_GET,'exe', FILTER_DEFAULT);

                        if (!empty($get)):

                            if ($get == 'restrito'):
                                MGErro('Acesso Negado, entre com usuário e senha para ter acesso ao painel de controle', MG_ERROR);
                            elseif ($get == 'logoff'):
                                MGErro('Sessão encerrada com sucesso.', MG_ACCEPT);                               
                            endif;
                        endif;
                        ?>  
                    
                    
                    <h1 class="h3 mb-3 font-weight-normal"><b>Login</b></h1> 

                        <label  for="endereço de e-mail"> 
                            <input type="email" name="user" class="form-control" placeholder="e-mail" autofocus/> 
                        </label>



                        <label for="senha"> 
                            <input type="password" name="pass" class="form-control" placeholder="senha"/> 
                        </label>

                        <div class="checkbox mb3">                 
                            <label>
                                <input type="checkbox" value="remember-me">
                                Lembre-me
                            </label>
                        </div>
                         <div >
                                <button class="btn btn-sm btn-primary" name="AdminLogin" value="Logar" type="submit">Enviar</button><br>
                         </div> 
                     
                </form>
        </div>              
                                           
        <script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="lib/js/bootstrap.min.js"></script>
    </body>                                    
    
</html>
