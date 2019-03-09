<?php

/**
 *  View.class [HELPER MVC - VIEW]
 *  Responsável por carregar o template, povoar, exibir a view, incluir arquivos php no sistema
 * Arquitetura MVC
 * @copyright (c)2018, Magnum Treinamento ltda
 */
class View{

    private static $Data;
    private static $Keys;
    private static $Values;
    private static $Template;    
    
    
    public static function Load($template){
        self::$Template = (string) $template;
        self::$Template = file_get_contents(self::$Template. '.tpl.html');
        
    }
    
    public static function Show (array $Data) {
        
        self::setKeys($Data);
        self::setValues($Data);
        self::ShowView();
              
    }
    
    public static function Request ($file, array $Data)   {
        
        extract($Data);
        require "{$file}.inc.php";        
        
    }
    
    //PRIVATES / MÉTODOS PRIVADOS
    
    private static function setKeys ($Data) {
        self::$Data = $Data;
        self::$Keys = explode('&', '#'. implode('#&#', array_keys(self::$Data)) .'#');
       
        
    }
    private static function setValues ($Data) {
        
        self::$Values = array_values(self::$Data);
        
    }
    
    private static function ShowView (){
        
        echo str_replace(self::$Keys, self::$Values, self::$Template);
        
    }
        
    
         
    }
    
    
    
