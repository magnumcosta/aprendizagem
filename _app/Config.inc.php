<?php

// Configurações do site ####################

define('HOST', 'localhost');
define('USER', 'root');
define('PASS', 'poderos1982');
define('DBSA', 'wsphp');

//  Auto load de classes ####################


function __autoload($class) {


    $cDir = ['Conn','helpers-ajuda','Models'];
    $iDir = NULL;

    foreach ($cDir as $dirName):
        
        if (!$iDir && file_exists(__DIR__ . "//{$dirName}//{$class}.class.php") && !is_dir(__DIR__ . "//{$dirName}//{$class}.class.php")):
            include_once (__DIR__ . "//{$dirName}//{$class}.class.php");
            $iDir = True;
        endif;

    endforeach;

    if (!$iDir):

        trigger_error(" Não foi possivel incluir o arquivo = {$class}.class.php", E_USER_ERROR);
        die;


    endif;
}

//Tratamento de erros  ######################
//CSS Constantes

define("WS_ACCEPT", 'accept');
define('WS_INFOR', 'infor');
define('WS_ALERT', 'alert');
define('WS_ERROR', 'error');


define("MG_ACCEPT", 'alert-success');
define('MG_INFOR', 'alert-primary');
define('MG_ALERT', 'alert-warning');
define('MG_ERROR', 'alert-danger');

//WSErro ::  Exibe Erros Lançados :: Front 

function WSErro($ErrMsg, $ErrNo, $ErrDie = Null) {

    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
    echo"<p class =\"trigger {$CssClass}\">{$ErrMsg}<span class = \"ajax_close\"></span></p>";
    if ($ErrDie):
        die;
    endif;
}

function MGErro($ErrMsg, $ErrNo, $ErrDie = Null) {

    //$CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
    $CssAlert = ($ErrNo === E_USER_NOTICE ? MG_INFOR :($ErrNo == E_USER_WARNING ? MG_ALERT:($ErrNo == E_USER_ERROR ? MG_ERROR : $ErrNo)));
    
    echo "<div class=\"alert {$CssAlert}\" role=\"alert\">{$ErrMsg}</div>";
    //echo"<p class =\"trigger {$CssClass}\">{$ErrMsg}<span class = \"ajax_close\"></span></p>";
    if ($ErrDie):
        die;
    endif;
}


//PHPErro :: Personaliza o gatilho do PHP   

function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));

    echo "<p class =\"trigger {$CssClass}\">";
    echo"<b>Erro na linha: #{$ErrLine} ::</b>{$ErrMsg}<br>";
    echo"<small>{$ErrFile}</small>";
    echo "<span class = \"ajax_close\"></span></p></p>";

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

set_error_handler('PHPErro');
