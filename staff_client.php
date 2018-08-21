<?php
/**
 * Created by PhpStorm.
 * User: dzyol
 * Date: 4/3/2018
 * Time: 8:24 PM
 */

header('Access-Control-Allow-Origin:http://localhost:63342');



$nickname = preg_replace_callback('/./u',function (array $match) {return strlen($match[0]) >= 4 ? '' : $match[0];},$_REQUEST['nickname']);
$avatar = "https://wx.qlogo.cn/mmopen/vi_32/Q3auHgzwzM4EqBc8uR49mKLSd9sIGRVaDicnhJqWQZ04ico6qkBEMBJtjB6N1BIy7icyyCxJ8fy6TC8h7PdXBRFo8qLHOFQAVyS/0";
$openid = isset($_REQUEST['openid'])?$_REQUEST['openid']:'';
$customer_text = isset($_REQUEST['customer_text'])?$_REQUEST['customer_text']:'';



//$conversation = "<div class='item left'><div class='img'></div><div class='content'>".$customer_text."</div></div>";
$conversation = "";


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <title>Title</title>

    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var conversation = "<?php echo $conversation; ?>"
            var new_text = "1"
            var openid = "<?php echo $openid;?>"
            var nickname = "<?php echo $nickname;?>"
            $("#form1").submit(function () {
                var question = document.getElementById("question").value;
                conversation += "<?php echo "<div class='item right'><img class='img' src='".$avatar."'><div class='content'>";?>" + question + "</div></div>"
                $.ajax({
                    type: "GET",
                    url: "https://wxbeta.ieasygo.cn/easygo/wx/customer_service/send_customer_msg.php",
                    data: {question:question,openid:openid},
                    dataType: 'text',
                    crossDomain: true,
                    success: function (data) {
                        $("#conversation").html(conversation);



                    }
                })



                return false;
            });


            //自动获取客服信息
            var timer = setInterval(function(){
                console.log("timer")
                $.ajax({
                    type: "GET",
                    url: "https://wxbeta.ieasygo.cn/easygo/wx/customer_service/get_customer_msg.php",
                    data: {openid:openid},
                    dataType: 'text',
                    crossDomain: true,
                    success: function (data) {
                        console.log("data = ",data)
                        console.log("new_text = ",new_text)
                        console.log(data!=new_text)
                        if(data != new_text && data !=""){
                            conversation += "<div class='item left'><img class = 'img'><div class='content'>" + data + "</div></div>"
                            new_text = data
                            console.log("new_text",new_text)
                            $("#conversation").html(conversation);

                        }
                    }
                })
            },2000)







        });




    </script>

</head>
<style>
    *{padding: 0; margin: 0; box-sizing: border-box}
    html, body{height: 100%; min-height: 100%; background-color: #b9f8f6}

    .wrap{padding: 20px 20px 50px;}

    .input{ background-color: #f4f4f4; color: #333; display: flex; justify-content: space-between; position: fixed; bottom: 0; left: 0; width: 100%; z-index: 100;padding: 5px 10px}
    .input input, .input p{-webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;}
    .input input{width: 76%; border: 1px solid #ddd; height: 42px; line-height: 40px;}
    .input button{ background-color: #0d5aa7; color: #fff; text-align: center; width: 20%; height: 42px; line-height: 42px;}




    .item{display: flex; margin-bottom: 20px}
    .img{width: 40px; height: 40px; background-color: #f4f4f4}
    .content{display: inline-block; line-height: 30px; padding: 5px 10px; color: #000; font-size: 16px; background-color: #fff; border-radius: 6px; position: relative; max-width: 80%}
    .content:after{content: ''; border: 8px solid transparent; position: absolute; top: 12px}
    .left .img{margin-right: 20px}
    .left .content:after{border-right-color: #fff; left: -16px;}
    .right{flex-direction: row-reverse}
    .right .img{margin-left: 20px}
    .right .content{background-color: #a0e759; display: flex; flex-direction: row-reverse}
    .right .content:after{border-left-color: #a0e759; right: -16px;}
</style>
<body>
<div class="wrap">
    <div id="conversation">

    <?php
        echo $conversation;
    ?>
<!--    <span id="show">333</span>-->
    </div>


<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
    <div class="input">
        <input type="hidden" name="conversation" value="<?php echo $conversation; ?>">
        <input type="text" name="question" id="question" value="form1">
        <button type="submit" name="submit" id="submit">发送</button>
    </div>

</form>

</form>
</div>
</body>
</html>

