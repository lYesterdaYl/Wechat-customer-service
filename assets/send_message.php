<?php
    $is_needlogin = true;
    include_once("include/inc.php");
    include('Tool.php');
    include('sendmessage.php');
    $message=$_REQUEST['message'];
    $sql=$_REQUEST['sql'];
    $result = $mysqli->query($sql);
    $num_rows=mysqli_num_rows($result);
    $i=0;
    $j=0;
    while ($row = $result->fetch_array()){
        if($row['formid']!='' || $row['formid']!='the formId is a mock one' ||  $row['formid']!=null){
            echo $row['formid'];
            echo '<br>';
            //$res=(new sendMessage())->setData($row['openid'],$row['formid'],$message);
            //var_dump($res);
            $sql = "update astore_users set `formid`=''  where openid = '".$row['openid']."'";
            //echo $sql;
            $mysqli->query($sql);
        }
        // $res=
        // //var_dump($res);
        // if($res['errmsg']=='ok'){
        //     $i++;
        // }else{
        //     $j++;
        // }
    }
    // echo '<script>alert("群发'.$num_rows.'人，成功群发'.$i.'人，失败'.$j.'")</script>';

?>