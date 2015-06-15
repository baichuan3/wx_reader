<?php
class LogManager
{/*{{{*/
    static $LOG_PATH = '';

    static $_objs = array();
    static $_sql_log      = null;
    static $_biz_err_log  = null;
    static $_sys_err_log  = null;
    static $_biz_log      = null;
    static $_shake_log      = null;
    static $_shake_err_log      = null;
    static $_cash_log      = null;
    static $_cash_err_log      = null;
    static $_api_log      = null;

    public static function setLogPath($path)
    {/*{{{*/
        self::$LOG_PATH = $path;
    }/*}}}*/
	
    public static function getSqlLog()
    {/*{{{*/
        return self::getLogTpl( 'sql' );
    }/*}}}*/

    public static function getBizErrLog($only=false)
    {/*{{{*/
        return self::getLogTpl( 'biz_err', $only );
    }/*}}}*/

    public static function getSysErrLog()
    {/*{{{*/
        return self::getLogTpl( 'sys_err' );
    }/*}}}*/

    public static function getApiLog()
    {/*{{{*/
        return self::getLogTpl( 'api' );
    }/*}}}*/

    public static function getCashErrLog($only=false)
    {/*{{{*/
        return self::getLogTpl('cash_err',$only );
    }/*}}}*/

    public static function getCashLog()
    {/*{{{*/
        return self::getLogTpl( 'cash' );
    }/*}}}*/

    public static function getShakeLog()
    {/*{{{*/
        return self::getLogTpl( 'shake' );
    }/*}}}*/

    public static function getShakeErrLog()
    {/*{{{*/
        return self::getLogTpl( 'shake_err' );
    }/*}}}*/

    public static function getBizLog($only=false)
    {/*{{{*/
        return self::getLogTpl( 'biz', $only );
    }/*}}}*/

    private static function getLogTpl( $type, $only = false )
    {/*{{{*/
        $obj_name = '_'.$type.'_log';
        if(isset(self::$_objs[$obj_name]) and is_object( self::$_objs[$obj_name] ) )
        {
            return self::$_objs[$obj_name];
        }

        $fname = self::$LOG_PATH.$type.'_'.date('Ymd').'.log';
        if (!file_exists($fname))
        {
            touch($fname);
            chmod($fname, 0777);
            echo "toucher new file";
        }
        if ( $only )
        {
            $fname = self::$LOG_PATH.$type.'.log';
        }
        self::$_objs[$obj_name] = new LogObject( $fname );
        return self::$_objs[$obj_name];
    }/*}}}*/

}/*}}}*/
