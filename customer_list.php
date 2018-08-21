<?php
/**
 * Created by PhpStorm.
 * User: dzyol
 * Date: 4/3/2018
 * Time: 7:52 PM
 */

include_once("include/inc.php");


?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">

    <title>A-STORE</title>

    <link rel="stylesheet" href="assets/css/amazeui.min.css"/>

    <script src="assets/js/jquery.min.js"></script>
    <!--<![endif]-->
    <script src="assets/js/amazeui.min.js"></script>
    <script src="assets/js/app.js"></script>
</head>
<body>
<div class="am-cf am-padding">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">客服客户列表</strong></div>
</div>

<div class="am-g">
    <div class="am-u-sm-12">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
            <thead>
            <tr>
                <th>用户名</th><th>openid</th><th>问题</th><th>时间</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "select * from customer_service where staff is NULL ORDER BY insert_time";
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_array()) {

                ?>
                <tr>
                    <td><?php echo $row['nickname'];?></td>
                    <td><?php echo $row['openid'];?></td>
                    <td><?php echo $row['customer_text'];?></td>
                    <td><?php echo $row['insert_time'];?></td>
                    <td>
                        <form action="https://astore.kmud.net/test/customer_service/staff_client.php" method="post">
                            <input name="openid" type="hidden" value="<?php echo $row['openid'];?>">
                            <input name="nickname" type="hidden" value="<?php echo $row['nickname'];?>">
                            <input name="customer_text" type="hidden" value="<?php echo $row['customer_text'];?>">
                            <input type="submit" value="联系客户">
                        </form>
                    </td>
                    <td></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
