<?php

include "include/inc.php";
function p($arr){
   echo "<pre>";
   print_r($arr);
   echo "</pre>";
}

// --------------归属于Link.php的代码---------------------
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
             $sqls = "insert into wx_sys(`access_token`,`update_timestamp`,`expires_in`,`appid`) values('$access_token','$update_timestamp','$expires_in','$appid')";
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

 define('TOKEN', 'meizi');

 $wechat = new wechat($access_token);
$wechat->checkSignature();
//$wechat->getUserLists($mysqli);//全部的用户都拉完
// $wechat->getNextUserLists($mysqli);//增量拉取用户都拉完

class  wechat{
       public $access_token;
       public function __construct($access_token){
          $this->access_token = $access_token;
       }
       public function checkSignature()

       {

           $echostr = $_GET["echostr"];

           $signature = $_GET["signature"];

           $timestamp = $_GET["timestamp"];

           $nonce = $_GET["nonce"];

           $token = TOKEN;

           $tmpArr = array($token, $timestamp, $nonce);

           sort($tmpArr, SORT_STRING);

           $tmpStr = implode( $tmpArr );

           $tmpStr = sha1( $tmpStr );
           if( $tmpStr == $signature ){
               return $echostr;
               exit() ;
          }
       }

       public function responseMsg(){

           $xml = file_get_contents("php://input");
           $obj = simplexml_load_string($xml);

           // 关注事件执行
           if($obj->Event=='subscribe'){
               $content = "Hi，欢迎关注EasyGo未来便利店！";
               // 去获取用户的unionID
               $access_token = $this->access_token;
              $openid = $obj->FromUserName;
              $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
              $content = $this->curlSend($url);
              $content = json_encode($content);
           }elseif($obj->Event=='unsubscribe'){
            // 取消关注
               $content = "取消关注!";
           }

           $reply =$this->replyWord($obj,$content);
           echo  $reply ;

       }

       private function  record($data){

           $file ='log.txt';
           file_put_contents($file, "\n\r"." ".$data."\n\r".date('Y-m-d H:i'));


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
       // 晚上定量更对应的
       public function getAllUsers($obj){
           $access_token = $this->access_token;
           $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
           $list = $this->curlSend($url);
           $len =  count($list['data']['openid']);
           for($i=0;$i<$len;$i++){
            $arrlist[] = "'".$list['data']['openid'][$i]."'";
           }
           $all = implode(",",$arrlist);
           $sql = "delete from is_fllow where openid not in($all); ";
           $result = $obj->query($sql);
           echo "all download success!";
           // $this->getUserMsg($obj,$arrlist);
       }
        // 一次性拉取全部关注的用户列表
        public function getUserLists($obj){
            $access_token = $this->access_token;
            $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
            $list = $this->curlSend($url);
            $len =  count($list['data']['openid']);
            for($i=0;$i<$len;$i++){
             $arrlist[] = $list['data']['openid'][$i];
            }
            $this->getUserMsg($obj,$arrlist);
        }
        // 选择性的拉取部分，即增量拉取
        public function getNextUserLists($obj){
            $access_token = $this->access_token;
            // $sql = "select id,next_openid from wx_subscribe_next limit 1";
            // $result = $obj->query($sql);
            // $row = $result->fetch_assoc();
            // if(empty($row)){
            //   $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
            //   $list = $this->curlSend($url);
            //   // 插入记录的节点数据到数据库
            //    $sql = "insert into wx_subscribe_next(`total`,`count`,`next_openid`) values('".$list['total']."','".$list['count']."','".$list['next_openid']."')";
            //    $result = $obj->query($sql);
            // }else{
               $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token."&next_openid=om76JwjYYn3EuuWqmv5u6J3lqXSY";
               $list = $this->curlSend($url);
               // 存在拉取才执行下面
               if(!empty($list['count'])){
                   for($i=0;$i<$len;$i++){
                     $arrlist[] = $list['data']['openid'][$i];
                   }
                 $sql = "update wx_subscribe_next set total='".$list['total']."',count='".$list['count']."',next_openid='".$list['next_openid']."'";
                 $result = $obj->query($sql);
                 $this->getUserMsg($obj,$arrlist);
              // }
            }
        }
        // 获取一个用户的信息
        public function getUserMsg($obj,$arr){

          $access_token = $this->access_token;
          // $conn = mysqli_connect("119.29.183.138:3306","root","slava-astore!@&","a-store");
          // foreach ($arr as $k => $val) {
          $len = count($arr);
          for($i=0;$i<$len;$i++){
              $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$arr[$i]."&lang=zh_CN";
              $data = $this->curlSend($url);
              // 存到数据库里面去
              // $sql = "insert into is_fllow(`subscribe`,`unionid`,`nickname`,`subscribe_time`,`openid`) values('".$data['subscribe']."','".$data['unionid']."','".$data['nickname']."','".$data['subscribe_time']."','".$data['openid']."')";
              $tmpStr =  preg_replace_callback('/./u',function (array $match) {return strlen($match[0]) >= 4 ? '' : $match[0];},$data['nickname']);
              $sql = "insert into astore_userinfo(`nickname`,`openid`) values('".$tmpStr."','".$data['openid']."')";
             $result = $obj->query($sql);
          }
          echo "success<br>";
        }

        // curlSend获取数据方法
        public  function  curlSend($url,$data=''){
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

 }
