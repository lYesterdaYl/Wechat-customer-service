<?php
/**
  * wechat php test
  */

$user_agent = $_SERVER['HTTP_USER_AGENT'];


//define your token
define("TOKEN", "meizi");


$db_server = '119.29.128.178:3306';
$db_acc = 'astore';
$db_pwd = 'astore123';
$db_name = 'a-store';
$db_code = 'utf8';

$mysqli = @new mysqli($db_server, $db_acc, $db_pwd);
$mysqli->query("set names " . $db_code);//编码转化
$select_db = $mysqli->select_db($db_name);



$appid ="wx4ec7f6571bed81b5";
$appsecret ="55875b25d3c9857a17c3a52de854adf1";


$sql = "select * from wx_sys where appid='$appid' limit 1";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

if(empty($row) || $row['update_timestamp'] < time()){//不存在或失效 调接口
    $url ="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;

    $result = curlSend($url);
    $update_timestamp=time()+7000;
    $access_token=$result['access_token'];
    print_r($result);
    if(empty($row)){
        $sqls = "insert into wx_sys(`access_token`,`update_timestamp`,`expires_in`,`appid`) values('$access_token','$update_timestamp','7200','$appid')";
        $result = $mysqli->query($sqls);
    }else{
        $id = $row['id'];
        $sqls = "update wx_sys set `access_token`='$access_token',`update_timestamp`='$update_timestamp', expires_in='7200' where `id`=$id ";
        echo $sqls;
        $result = $mysqli->query($sqls);
    }
}else{
    $access_token=$row['access_token'];
}

// 访问路径
function  curlSend($url,$data=''){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不进行证书验证
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不进行主机头验证
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //结果不直接输出在屏幕上
    $data && curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $data ? curl_setopt($ch, CURLOPT_POST, true):curl_setopt($ch, CURLOPT_POST, false);  //发送的方式
    curl_setopt($ch, CURLOPT_URL, $url);   //发送的地址
    $result =curl_exec($ch);
    curl_close($ch);
    $info =json_decode($result,true);
    return  $info ;
}


$sql = "insert into cs1 (data) values('1')";
$mysqli->query($sql);





$wechatObj = new wechat($access_token);
$wechatObj->valid();
$wechatObj->responseMsg();








































class wechat
{
    public $access_token;
    public function __construct($access_token){
        $this->access_token = $access_token;
    }

    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        $db_server = '119.29.128.178:3306';
        $db_acc = 'astore';
        $db_pwd = 'astore123';
        $db_name = 'a-store';
        $db_code = 'utf8';

        $mysqli = @new mysqli($db_server, $db_acc, $db_pwd);
        $mysqli->query("set names " . $db_code);//编码转化
        $select_db = $mysqli->select_db($db_name);


        $xml = file_get_contents("php://input");
        $obj = simplexml_load_string($xml);
        $openid = $obj->FromUserName;

        $content = $obj->Content;
//-------------------------------------------------------------------


        $user_agent = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.4620.400 QQBrowser/9.7.13014.400";
//echo $user_agent;


        $urls = "https://openapi.baidu.com/public/2.0/yeying/system/robot";
        $parData=array(
            'prefix'=>"zaojiu",
            'question'=>$content,
            'token'=>"e3dc00f4-836b-4661-a372-ef00049125c8",
            'userId'=>$openid,
            'user_agent'=>$user_agent
        );
        $datas=$this->httpPost($urls,$parData);

        $datas = json_decode($datas, TRUE);


        if(isset($datas['result']['data'][0]['question'])){
            $parData=array(
                'prefix'=>"zaojiu",
                'question'=>$datas['result']['data'][0]['question'],
                'token'=>"e3dc00f4-836b-4661-a372-ef00049125c8",
                'userId'=>$openid,
                'user_agent'=>$user_agent
            );
            $datas = json_decode($this->httpPost($urls, $parData),TRUE);
            $answer = $datas['result']['data'][0]['answer'];
        }
        else{
            if($datas['result']['match_level'] != "NONE"){
                $answer = $datas['result']['data'][0]['answer'];
            }
            else{
                $answer = "不好意思,你的问题我不能理解";
            }

        }













//
//
//
//        $urls = "https://wxbeta.ieasygo.cn/easygo/wx/customer_service/test.php?question=".$content;
//
//        $datas = file_get_contents($urls);






























//--------------------------------------------------------------------------
        $sql = "insert into cs1 (data) values('".$datas."')";
        $mysqli->query($sql);

        $data = '{
                    "touser":"'.$openid.'",
                    "msgtype":"text",
                    "text":
                            {
                                 "content":"'.$answer.'"
                            }
                }';

        $access_token = $this->access_token;
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token&openid=$openid&lang=zh_CN";

        $sql = "insert into cs1 (data) values('".$url."')";
        $mysqli->query($sql);
        echo $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);





//        $content = $this->curlSend($url,$data);
//        $content = json_encode($content);
//
//        $reply =$this->replyWord($obj,$content);
//        echo  $reply ;

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

    private function replyWord($obj,$content){
        // $content=$obj->FromUserName;
        $tpl ='<xml>

<ToUserName><![CDATA[%s]]></ToUserName>

<FromUserName><![CDATA[%s]]></FromUserName>

<CreateTime>%s</CreateTime>

<MsgType><![CDATA[text]]></MsgType>

<Content><![CDATA[%s]]></Content>

</xml>' ;

        return   sprintf($tpl,$obj->FromUserName,$obj->ToUserName,time(),$content) ;

    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

}

?>