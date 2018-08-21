<?php
/**
 * Created by PhpStorm.
 * User: dzyol
 * Date: 3/27/2018
 * Time: 12:23 AM
 */

//$nickname = isset($_REQUEST['nickname'])?$_REQUEST['nickname']:'';
$nickname = preg_replace_callback('/./u',function (array $match) {return strlen($match[0]) >= 4 ? '' : $match[0];},$_REQUEST['nickname']);

$avatar = isset($_REQUEST['avatar'])?$_REQUEST['avatar']:'';

echo $nickname." ".$avatar;

$conversation = isset($_REQUEST['conversation'])?$_REQUEST['conversation']:'';
$content = isset($_REQUEST['question'])?$_REQUEST['question']:'';


if(isset($_REQUEST['conversation'])) {
    $conversation .= "<br><B>我:</B><br>" . $content . "<br>";
}

if(isset($_REQUEST['question'])) {
    $urls = "https://wxbeta.ieasygo.cn/easygo/wx/customer_service/test.php?question=" . $content;
    $datas = file_get_contents($urls);

    $conversation .= "<B>System</B><br>".$datas;
    echo $conversation;


}

?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">

    <title>Customer Service</title>

    <link rel="stylesheet" href="assets/css/amazeui.min.css"/>

    <script src="assets/js/jquery.min.js"></script>
    <!--<![endif]-->
    <script src="assets/js/amazeui.min.js"></script>
    <script src="assets/js/app.js"></script>
</head>
<body>
<div class="am-cf am-padding">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">聊天</strong></div>
</div>

<div class="am-g">
    <div class="am-u-sm-12">
        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="conversation" value="<?php echo $conversation; ?>">
            <table class="am-table am-table-bd am-table-striped admin-content-table">
                <tbody>
                <tr>
<!--                    <td><img src="https://wx.qlogo.cn/mmopen/vi_32/Q3auHgzwzM4EqBc8uR49mKLSd9sIGRVaDicnhJqWQZ04ico6qkBEMBJtjB6N1BIy7icyyCxJ8fy6TC8h7PdXBRFo8qLHOFQAVyS/0"></td>-->
                    <td><input type="text" name="question" id="question" value="1"></td>
                    <td><input type="submit" name="submit" id="submit" value="提交"></td>

                </tr>
                <tr>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

</body>
</html>







<?php




?>