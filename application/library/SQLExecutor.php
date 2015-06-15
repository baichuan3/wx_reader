<?php
class SQLExecutor
{/*{{{*/
    const CONN_LONG  = true;
    const CONN_SHORT = false;

    private $_dbh = null;
    private $_log = null;

    public function __construct( $host, $user, $pass, $name, $port = '3306', $ctype = '', $charset = 'utf-8' )
    {/*{{{*/
        try
        {
            if (!UtlsBox::isInt($port))
            {
                $port = '3306';
            }
            if ( '' == $ctype )
            {
                $ctype = self::CONN_SHORT;
            }
            $this->_dbh = new PDO( 'mysql:host='.$host.';dbname='.$name.';port='.$port, $user, $pass,
                array( PDO::ATTR_PERSISTENT => $ctype ) );
            $this->_dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $this->_dbh->query( 'SET NAMES '.$charset );
        }
        catch (Exception $e)
        {
        $log = LogSvc::getSysErrLog();
        $log->log($e);
            return null;
        }
    }/*}}}*/

    public function regLogObj( $obj )
    {/*{{{*/
        $this->_log = $obj;
    }/*}}}*/

    public function query( $sql, $values = array() )
    {/*{{{*/
        $this->logSql($sql,$values);

        try
        {
            $i   = 0;
            $sth = $this->_dbh->prepare( $sql );

            if ( !empty( $values ) )
            {
                foreach ( $values as $value )
                {
                    $sth->bindValue( ++$i, $value );
                }
            }

            $time = microtime();
            if ( $sth->execute() )
            {
                $time = microtime() - $time;
//                var_dump($time);
                $result = $sth->fetchAll( PDO::FETCH_ASSOC );
                if ( is_array( $result ) && array_key_exists( 0, $result ) )
                {
                    return $result[0];
                }
            }
            $time = microtime() - $time;
//            var_dump($time);

            return null;
        }
        catch (Exception $e)
        {
            $this->logError($e);
            return null;
        }
    }/*}}}*/

    public function querys( $sql, $values = array() )
    {/*{{{*/
        $this->logSql($sql,$values);

        try
        {
            $i   = 0;
            $sth = $this->_dbh->prepare( $sql );
            if ( !empty( $values ) )
            {
                foreach ( $values as $value )
                {
                    $sth->bindValue( ++$i, $value );
                }
            }

            $time = microtime();
            if ( $sth->execute() )
            {
                $time = microtime() - $time;
//                var_dump($time);
                return $sth->fetchAll( PDO::FETCH_ASSOC );
            }
            $time = microtime() - $time;
//            var_dump($time);
            return array();
        }
        catch (Exception $e)
        {
            $this->logError($e);
            return array();
        }
    }/*}}}*/

    private function formatValues( $values )
    {/*{{{*/
        $result = array();
        foreach ($values as $k => $v )
        {
        	if ( is_string( $v ) )
        	{
        		$result[$k] = "'".$v."'";
        		continue;
        	}
        	if ( is_null( $v ) )
        	{
        		$result[$k] = 'null';
        	}
        	$result[$k] = $v;
        }
        return $result;
    }/*}}}*/

    private function logSql( $sql, $values = array() )
    {/*{{{*/
    	if ( is_null( $this->_log ) )
    	{
    		return;
    	}

    	if ( empty( $values ) )
    	{
//            var_dump( $str );
    		$this->_log->log( $sql );
    		return;
    	}

    	$str = str_replace( '%', '{#}', $sql );
        $str = vsprintf( str_replace( '?', '%s', $str ), $this->formatValues( $values ) );
        $str = str_replace( '{#}', '%', $str );
        $this->_log->log( $str );
//        echo "<!--$str-->";
//       var_dump( $str );
    }/*}}}*/

    public function exeNoQuery( $sql, $values = array() )
    {/*{{{*/
        try
        {
            $this->logSql($sql,$values);

            $i   = 0;
            $sth = $this->_dbh->prepare( $sql );
            foreach ( $values as $value )
            {
                $sth->bindValue( ++$i, $value );
            }
            $time = microtime();
            if ( !$sth->execute() )
            {
                $time = microtime() - $time;
//                var_dump($time);
                return false;
            }
            $time = microtime() - $time;
//            var_dump($time);
            return $sth->rowCount();
        }
        catch (Exception $e)
        {
            $this->logError($e);
            return false;
        }
    }/*}}}*/

    public function execute( $sql, $values = array() )
    {/*{{{*/
        try
        {
            $this->logSql($sql,$values);

            $i   = 0;
            $sth = $this->_dbh->prepare( $sql );
            foreach ( $values as $value )
            {
                $sth->bindValue( ++$i, $value );
            }
            $time = microtime();
            $res = $sth->execute();
            $time = microtime() - $time;
//            var_dump($time);
            return $res;
        }
        catch (Exception $e)
        {
            $this->logError($e);
            return false;
        }
    }/*}}}*/

    public function beginTrans()
    {/*{{{*/
        $this->_dbh->beginTransaction();
    }/*}}}*/

    public function commit()
    {/*{{{*/
        return $this->_dbh->commit();
    }/*}}}*/

    public function rollback()
    {/*{{{*/
        return $this->_dbh->rollback();
    }/*}}}*/

    public function getLastInsertID()
    {/*{{{*/
        return ( int ) $this->_dbh->lastInsertId();
    }/*}}}*/

    private function logError($e)
    {/*{{{*/
        $log = LogSvc::getSysErrLog();
        $log->log($e);
    }/*}}}*/
}/*}}}*/
?>
