<?php
/**
 * PdoManager
 *by zpw
 */

class PdoManager
{
    private $_pdo = null;
    private static $_host = '';
    private static $_user = '';
    private static $_passwd = '';
    private static $_db = '';
    private static $_port = 3306;
    private static $_charset = 'utf8';
    

    private function __construct()
    {/*{{{*/
        $dns = "mysql:host=".self::$_host.";dbname=".self::$_db.";port=".self::$_port;
        $this->_pdo = new PDO($dns, self::$_user, self::$_passwd);
        $this->_pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $this->_pdo->query( 'SET NAMES '.self::$_charset);
    }/*}}}*/

    public static function initManager($host, $user, $passwd, $db,$port = 3306)
    {/*{{{*/
        self::$_host = $host;
        self::$_user = $user;
        self::$_passwd = $passwd;
        self::$_db = $db;    
        self::$_port = $port;    
    }/*}}}*/

    public static function instance()
    {/*{{{*/
        static $inst = null;
        if(empty(self::$_host) || empty(self::$_user) || empty(self::$_db))
            return null;

        try
        {
            if(null == $inst)
                $inst = new PdoManager();
        } catch (PDOException $e)
        {
                print_r($e);
                $inst = null;
        }

        return $inst;
    }/*}}}*/

    public function getPdo()
    {/*{{{*/
        return $this->_pdo;
    }/*}}}*/

    public function __destruct()
    {/*{{{*/
        $this->_pdo = null;
    }/*}}}*/
}

?>
