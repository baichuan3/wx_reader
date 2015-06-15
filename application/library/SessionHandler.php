<?php
class SessionHandler {
    private $_memcache;
    private $_expire_time;
    public function __construct(Memcache $memcache,$expire_time) {
        $this->_memcache = $memcache;
        $this->_expire_time = $expire_time;
        session_set_save_handler(
            array($this,'open'),
            array($this,'close'),
            array($this,'read'),
            array($this,'write'),
            array($this,'destroy'),
            array($this,'clean')
        );
    }

    public function open($save_path, $session_name)
    {
            return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        return $this->_memcache->get($id);
    }

    public function write($id, $data)
    {
        $this->_memcache->set($id,$data,0,$this->_expire_time);
        return true;
    }

    public function destroy($id)
    {
        return $this->_memcache->delete($id);
    }
    public function clean($expire)
    {
        return true;
    }
}
?>
