<?php

class SimplePDO {

    protected $_pdo;
    public $_errno;
    public $_error;
    public $_rowcount;
    private $_log = null;

    public function __construct($pdo = null) {/* {{{ */
        if (!$pdo) {
            $dbconf = Yaf_Registry::get("config")->get("app")->get("dbconf");
//            $dbconf = Yaf_Registry::get("config")->("app")->("dbconf");
//            print_r($dbconf);
            $hostname = $dbconf->host;
            $dbusername = $dbconf->username;
            $dbpassword = $dbconf->password;
            $dbname = $dbconf->dbname;
            $port = $dbconf->port;
//            print_r($hostname);
            PdoManager::initManager($hostname, $dbusername, $dbpassword, $dbname, $port);
            $this->_pdo = PdoManager::instance()->getPdo();
        } else {
            $this->_pdo = $pdo;
        }

        LogManager::setLogPath(Yaf_Registry::get('config')->application->log_path);

        if (!$this->_pdo) {
            echo "数据库配置有误";
            exit();
        }
    }

/* }}} */

    public function regLogObj($obj) {/* {{{ */
        return $this->_log = $obj;
    }

/* }}} */

    public function getPdo() {/* {{{ */
        return $this->_pdo;
    }

/* }}} */

    private function logSql($sql, $values = array()) {/* {{{ */
        if (is_null($this->_log)) {
            return '';
        }
        $this->_log->log($sql);
    }

/* }}} */

    public function Fetch($sql) {/* {{{ */
        $this->logSql($sql);
        $pdo = $this->_pdo;
        try {
            $stmt = $pdo->query($sql);
            $errinfo = $pdo->errorInfo();
            $this->dealerror($errinfo);
            if ($this->_errno != 0) {
                //exit("Mysql Error:" . $this->_error);
                exit();
            }
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = null;
            return $row;
        } catch (PDOException $e) {
            //print $e->getMessage();
            exit;
        }
    }

/* }}} */

    public function FetchAll($sql) {/* {{{ */
        $this->logSql($sql);
        $pdo = $this->_pdo;
        try {
            $stmt = $pdo->query($sql);
            //	    $this->_rowcount = $stmt->rowCount();
            $errinfo = $pdo->errorInfo();
            $this->dealerror($errinfo);
            if ($this->_errno != 0) {
                //exit("Mysql Error:" . $this->_error);
                print_r($this->_errno);
                exit();
            }
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            return $rows;
        } catch (PDOException $e) {
            print $e->getMessage();
            exit;
        }
    }

/* }}} */

    public function Execute($sql) {/* {{{ */
        $pdo = $this->_pdo;
        try {
            $stmt = $pdo->exec($sql);
            $errinfo = $pdo->errorInfo();
            $this->dealerror($errinfo);
            if ($this->_errno != 0) {
                //exit("Mysql Error:" . $this->_error);
                exit();
            }
            $this->LogSQL($sql);
            return $stmt;
        } catch (PDOException $e) {
            //print $e->getMessage();
            exit;
        }
    }

/* }}} */

    public function lastInsertId() {/* {{{ */
        return $this->_pdo->lastInsertId();
    }

/* }}} */

    public function dealerror($errinfo) {/* {{{ */
        if ($errinfo) {
            if (isset($errinfo[1]))
                $this->_errno = $errinfo[1];
            if (isset($errinfo[2]))
                $this->_error = $errinfo[2];
        }
    }

/* }}} */
}

?>
