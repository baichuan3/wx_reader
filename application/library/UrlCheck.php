<?php
set_include_path(get_include_path().":".dirname(__FILE__));
include("Qihoo/Cloud.php");
//include("Qihoo/Cloud/ExtendInterpreter/Icp.php");
include("Qihoo/Cloud/ExtendInterpreter/Trust.php");
include("Qihoo/Cloud/ExtendInterpreter/Cg.php");
include("Qihoo/Cloud/ExtendInterpreter/Se.php");

class UrlCheck{
    public function check($urls){
        $cloud = Qihoo_Cloud::factory("url");
       //$cloud->addInterpreter("icp.info", new Qihoo_Cloud_ExtendInterpreter_Icp());
        $cloud->addInterpreter("trust.info", new Qihoo_Cloud_ExtendInterpreter_Trust());
        $cloud->addInterpreter("cg.info", new Qihoo_Cloud_ExtendInterpreter_Cg());
        $cloud->addInterpreter("se.info", new Qihoo_Cloud_ExtendInterpreter_Se());
        return $cloud->query($urls);
    }
}
