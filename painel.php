<?php
session_start();
require('./_app/Config.inc.php');
$login = new sessao(3);
$logoff = filter_input(INPUT_GET,'logoff',FILTER_VALIDATE_BOOLEAN);
$getExe= filter_input (INPUT_GET,'exe',FILTER_DEFAULT);
$login->CheckLogin();
if (!$login->CheckLogin()):
    unset ($_SESSION['userlogin']);
    header('location: index.php?exe=restrito');
else:
    $userLogin = $_SESSION['userlogin'];
endif;

if ($logoff):
    unset($_SESSION['userlogin']);
    header('location: index.php?exe=logoff');   
endif;
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>MAGNUM PAINEL</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,800' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="lib/bootstrap/bootstrap.min.css" />  
        <link rel="stylesheet" href="css/painel.css" />         
    </head>
    
    <body class="painel">
    <?php
    if (isset($getExe)):
        $linkPara = explode("/", $getExe);
    else:
        $linkPara = array();  
    endif;
    ?>           
        <header class="navadmin">            
            
            <div class="nav">
                <ul class="nav-bar">
                    <li class="username">Olá, <?= $userLogin['user_name'];?><?= $userLogin['user_lastname'];?> ! </li>
                    <li><a href="painel.php?logoff=true">sair</a></li>                    
                </ul>
            </div>
            
            <div class="titulo-painel">
            <h1><b>PAINEL DE CONTROLE</b></h1>
            </div>
            <ul class="navegador">
                <li>Categorias</a> 
                    <ul class="sub-list">
                        <li><a href="painel.php?exe=categories/create">Criar Categoria</a></li>
                        <li><a href="painel.php?exe=categories/update">Editar Categorias</a></li>
                    </ul>
                </li>                
                <li >Posts</a>
                    <ul class="sub-list">
                        <li><a href="painel.php?exe=posts/create">Criar Post</a></li>
                        <li><a href="painel.php?exe=posts/index">Editar Posts</a></li>
                    </ul>
            </ul>   
                
        </header>
        
        <section class="exePainel">
            
            <div id="painel">                
                 <?php   //controlador PHP (MVC)    
                    if (!empty($getExe)):
                       $includepatch = __DIR__ .'/'.strip_tags(trim($getExe).'.php');
                    else:
                       $includepatch = __DIR__ . '/home.php';
                   endif;

                    if (file_exists($includepatch)):
                        require_once($includepatch);
                    else:
                        echo "<div class=\"content notfound\">";
                        MGErro("<b>Erro ao incluir tela:</b> Erro ao incluir o controller /{$getExe}.php!", MG_ERROR);
                        echo "</div>";
                    endif;
                 ?> 
            </div>
        </section>
        
        <footer>

            <div class="rodape">
                
                <p id="sing">© cursos MAGNUM - 2019</p>

            </div>

        </footer>
        
       
        
        <script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="lib/js/bootstrap.min.js"></script>
        
    </body>
</html>
