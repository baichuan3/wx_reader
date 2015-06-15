<?php
class MemCacheDriver
{/*{{{*/
    const FLAG_NO  = false;
    const FLAG_YES = true;

    private static $_ins  = null;
    private $_cacheDriver;
    private $_flag = null;

    public static function ins()
    {/*{{{*/
        if(self::$_ins == null){
            self::$_ins = new MemCacheDriver(UtilsBox::getMem_confs());
        } 
        return self::$_ins; 
    }/*}}}*/

    public function __construct( $servers, $flag = self::FLAG_NO )
    {/*{{{*/
//        $memcacheDrivers = array(
//            array('host' => '221.194.175.28', 'port' => '11214'),
//            array('host' => '221.194.175.29', 'port' => '11214'),
//            array('host' => '124.238.243.68', 'port' => '11214'),
//            array('host' => '124.238.243.69', 'port' => '11214'),
//        );

        $this->_cacheDriver = new Memcache();
        foreach ( $servers as $s )
        {
            $this->_cacheDriver->addServer( $s['host'], $s['port'] );
        }
        $this->_flag = $flag;
    }/*}}}*/

    public function get( $key )
    {/*{{{*/
        return $this->_cacheDriver->get( $key );
    }/*}}}*/

    public function set( $key, $value, $expire = 600)
    {/*{{{*/
      //  LogSvc::getBizLog()->log('set '.$key);
        return $this->_cacheDriver->set( $key, $value, $this->_flag, $expire );
    }/*}}}*/

    public function add( $key, $value, $expire = 600 )
    {/*{{{*/
        return $this->_cacheDriver->add( $key, $value, $this->_flag, $expire );
    }/*}}}*/

    public function replace( $key, $value, $expire = 600)
    {/*{{{*/
        return $this->_cacheDriver->replace( $key, $value, $this->_flag, $expire );
    }/*}}}*/
    
    public function delete( $key )
    {/*{{{*/
       // LogSvc::getBizLog()->log('del '.$key);
        return $this->_cacheDriver->delete( $key );
    }/*}}}*/

    public function flush()
    {/*{{{*/
        //LogSvc::getBizLog()->log('flush');
        return $this->_cacheDriver->flush();
    }/*}}}*/

    public function increment( $key, $value )
    {/*{{{*/
        return $this->_cacheDriver->increment( $key, $value );
    }/*}}}*/

    public function decrement( $key, $value )
    {/*{{{*/
        return $this->_cacheDriver->decrement( $key, $value );
    }/*}}}*/

    public function update( $key, $value )
    {/*{{{*/
        $value = (int) $value;
        if ( $value > 0 )
        {
            return $this->increment( $key, $value );
        }
        return $this->decrement( $key, abs( $value ) );
    }/*}}}*/

    public function close()
    {/*{{{*/
        return $this->_cacheDriver->close();
    }/*}}}*/

    public function __destruct()
    {/*{{{*/
        $this->close();
    }/*}}}*/
}/*}}}*/
?>
