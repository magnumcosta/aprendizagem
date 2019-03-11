<?php

/**
 *  Conn.class [Conexao]
 *  Classe Abstrata de Conexão. Padrão singleton. 
 * Retorna um objeto  PDO pelo método estático  getConn()  
 * @copyright (c) 2018, Magnum Treinamento ltda
 */
class Conn {

    private static $Host = HOST;
    private static $User = USER;
    private static $Pass = PASS;
    private static $Dbsa = DBSA;

    /** @var PDO */
    private static $Connect = null;

    /**
     * Conecta com o banco de dados com o pattern singleton.
     * Retorna um objeto PDO!
     */
        private static function Conectar() {
        try {
            if (self::$Connect == null):
                $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                if ($hostname = 'magnum-mate-3450'):
                    $dsn = 'mysql:host=' . 'mysql' . ';dbname=' . self::$Dbsa;
                else:
                    $dsn = 'mysql:host=' . self::$Host . ';dbname=' . self::$Dbsa;
                endif;

                //$dsn = 'mysql:host=' . self::$Host . ';dbname=' . self::$Dbsa;
                $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
                self::$Connect = new PDO($dsn, self::$User, self::$Pass, $options);
            endif;
        } catch (PDOException $e) {
            PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
            die;
        }

        
        self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$Connect;
    }

    /** Retorna um objeto PDO Singleton Pattern. */
    public static function getConn() {
        return self::Conectar();
    }

}
