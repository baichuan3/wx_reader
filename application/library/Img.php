<?php 
require_once('/home/q/php/picasso_sdk/picasso_client.php');
class Img
{/*{{{*/
    const tokenKey = "so_rec_jiucuo";
    const tokenSecret = "c1d2c4d02b474343";

    public static function uploadToCloud($imgstream)
    {/*{{{*/
        $client = new PicassoClient(self::tokenKey,self::tokenSecret);
        $client->addRule("demo0"); 
//        $client->addRule("demo2",new PicaRule_crop(70,70));
        $r =  $client->proc($imgstream);
        if($r['STATUS'] == 'SUCC'){
            $r = $r['DATA']['demo0']['URL'][1];
            return $r; 
        }else{
           // Utools::Uerror_log('uploadToCloud img error:'.$r['ERRORMSG']);
            return false;
        }   
    }/*}}}*/
}
/*}}}*/

