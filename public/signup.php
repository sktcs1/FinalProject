<?php

    // ดึงตัวไฟล์ autoload
    require "../private/autoload.php";


    $Error      = "";
    $email      = "";
    $username   = ""; 

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        print_r($_POST);

        $email = $_POST['email'];
        
        #preg_match คือการสร้าง pattern ดักไว้ให้ตรงตามที่เรากำหนด
        if(!preg_match("/^[\w\-]+@[\w\-]+.[\w\-]+$/", $email))
        {
            $Error = "Please enter valid email";
        }

        $date = date("Y-m-d H:i:s");
        $url_address = get_random_string(60);

        $username = trim ($_POST['username']);
        if(!preg_match("/^[a-zA-Z]+$/", $username))
        {
            $Error = "Please enter valid username";
        }

        $username = esc($username);
        $password = esc($_POST['password']);


            //เช็คถ้ามี email นี้ลงทะเบียนแล้ว 
            $arr = false;
            $arr['email'] = $email;

            $query = "select * from users where email = :email limit 1";
            $stm = $connection ->prepare($query);
            $check = $stm->execute($arr);

            if($check){
                $data = $stm->fetchAll(PDO::FETCH_OBJ);
                if(is_array($data) && count($data) > 0){
                    
                    $Error = "Someone is already using that email";  
                    }
                }   

        // ถ้าไม่มีข้อผิดพลาดให้ insert เข้า Database 
        if($Error == ""){

        $arr['url_address'] = $url_address;
        $arr['username'] = $username;
        $arr['password'] = $password;
        $arr['email'] = $email;
        $arr['date'] = $date;

        $query = "insert into users (url_address,username,password,email,date) values (:url_address,:username,:password,:email,:date)";
        $stm = $connection ->prepare($query);
        $stm->execute($arr);
        echo $query;

        // เสร็จแล้วให้เด้งไปหน้า Login
        header("Location: login.php");
        die;

        }
    }

?>


<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body style="font-family: verdana">

    <!-- เปิด Style -->
    <style type="text/css">
        form{
            margin: auto;
            border: solid thin #aaa;
            padding: 10px;
            max-width: 200px;
        }

        #title {
            background-color: #7abf4b;
            padding: 1em;
            text-align: center;
            color: white;
        }

        #textbox{
            border: solid thin #aaa;
            margin-top: 6px;
            width: 98%;
            
        }
    </style>
    <!-- ปิด Style -->

    <!-- สร้าง form -->
    <form method="post">
        <div><?php
            if(isset($Error) && $Error !="")
            {
                echo $Error;
            }
        ?></div>
        <div id="title">Signup</div>
        <input id="textbox" type="text" name="username" value="<?=$username?>" required placeholder="Username"><br>
        <input id="textbox" type="email" name="email" value="<?=$email?>" required placeholder="Email" ><br>
        <input id="textbox" type="password" name="password" required placeholder="Password" > <br><br> 
        <input id="text-center" type="submit" value="Signup" text-align: center>
    </form>
    <!-- ปิด form -->
    
</body>
</html>