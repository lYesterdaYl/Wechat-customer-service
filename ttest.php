<?php
/**
 * Created by PhpStorm.
 * User: dzyol
 * Date: 3/28/2018
 * Time: 1:22 AM
 */


include_once('include/inc.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">

    $(document).ready(function (){
        $("#form1").submit(function () {
            $("#show").text("444");
        });
        $("#form2").submit(function () {
            $("#show").text("777");
//中间可以做一些ajax数据交互，让"777"为后台返回的值
            return false;
        });
        $("#btn").click(function () {
            $("#show").text("555");
        });
        $("#num3").keydown(function(e){ //按下键盘触发函数
            if(e.keyCode==13){ //如果按下的是回车
                $("#show").text("666"); //处理事件
            }
        });
    })

</script>

</head>
<body>

<form id="form1">
    表单一：<input type="text">
<input type="submit" value="在左侧输入框按回车或点击此按钮，可以看到文本值333一闪而过为444又回到333">
</form>

<form id="form2">
    表单二：<input type="text">
<input type="submit" value="在左侧输入框按回车或点击此按钮，可以看到文本值333改为777不变回去">
</form>

独立输入框（按回车更新文本为666）：<input type="text" id="num3">
<br>

<button id="btn">点击更改文本值为555</button>
<span id="show">333</span>

</body>
</html>