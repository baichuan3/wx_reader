<?php

class Bootstrap extends Yaf_Bootstrap_Abstract{

    private $_config;
    private $_db;

    public function _initConfig() {
        $this->_config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $this->_config);

        $this->_db = new SimplePDO();
        Yaf_Registry::set('db', $this->_db);
    }

    public function _initErrors(){
        if($this->_config->application->showErrors){
            ini_set('display_errors',"On");
            ini_set('error_reporting',E_ALL);
        }else{
            error_reporting (0);
            ini_set('display_errors','Off');
        }
    }

    public function _initRequest() {                                                       
        Yaf_Registry::set('request', new RequestValidator());                              
    } 

    public function _initPlugin(Yaf_Dispatcher $dispatcher) {
        //注册一个插件
        //$objSamplePlugin = new SamplePlugin();
        //$dispatcher->registerPlugin($objSamplePlugin);
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher) {
        $router = $dispatcher->getRouter();
        $route = new Yaf_Route_Simple("m", "c", "a");
        $router->addRoute("myroute", $route);		   
    }

    public function _initView(Yaf_Dispatcher $dispatcher){
    }

}
