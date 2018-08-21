<?php
/**
 * Created by PhpStorm.
 * User: dzyol
 * Date: 3/23/2018
 * Time: 1:38 AM
 */

public function get_ai_respond($question){
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $urls = "https://openapi.baidu.com/public/2.0/yeying/system/robot";
    $parData=array(
        'prefix'=>"zaojiu",
        'question'=>$question,
        'token'=>"e3dc00f4-836b-4661-a372-ef00049125c8",
        'userId'=>"3424243421",
        'user_agent'=>$user_agent
    );
    $datas=httpPost($urls,$parData);

    print_r($datas);
    echo "<pre><br>";

    $datas = json_decode($datas, TRUE);
    print_r($datas);


    if(!empty($datas['result']['data'][0]['question'])){
        $parData=array(
            'prefix'=>"zaojiu",
            'question'=>$datas['result']['data'][0]['question'],
            'token'=>"e3dc00f4-836b-4661-a372-ef00049125c8",
            'userId'=>"3424243421",
            'user_agent'=>$user_agent
        );
        print_r(json_decode($this->httpPost($urls, $parData),TRUE));
    }

}

public function httpPost($url, $param, $post_file=false){
    $oCurl = curl_init();
    if(stripos($url,"https://")!==FALSE) {
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
    }
    if (is_string($param) || $post_file) {
        $strPOST = $param;
    } else {
        $aPOST = array();
        foreach($param as $key=>$val){
            $aPOST[] = $key."=".urlencode($val);
        }
        $strPOST =  join("&", $aPOST);
    }
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($oCurl, CURLOPT_POST,true);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);
    if(intval($aStatus["http_code"])==200) {
        return $sContent;
    } else {
        return false;
    }
}