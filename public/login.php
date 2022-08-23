<?php

     require "../private/autoload.php";
     $Error = "";


    if($_SERVER['REQUEST_METHOD'] == "POST" && isset ($_SESSION['token']) && isset ($_POST['token']) && $_SESSION['token'] == $_POST['token'])
    {
        print_r($_POST);

        $email = $_POST['email'];
        if(!preg_match("/^[\w\-]+@[\w\-]+.[\w\-]+$/", $email))
        {
            $Error = "Please enter valid email";
        }

        $password = $_POST['password'];

        if($Error == ""){


            $arr['password'] = $password;
            $arr['email'] = $email;
            

            $query = "select * from users where email = :email && password = :password limit 1";
            $stm = $connection->prepare($query);
            $check = $stm->execute($arr);


            if($check){
    
                $data = $stm->fetchAll(PDO::FETCH_OBJ);
                if(is_array($data) && count($data) > 0){
                    
                    $data = $data[0];
                    $_SESSION['username'] = $data->username;
                    $_SESSION['url_address'] = $data->url_address;
                    header("Location: index2.php");
                    die;  
                }
            
            }
        }

        $Error = "Wrong email or password";

    }

    $_SESSION['token'] = get_random_string(60);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
    body {
        background-image: url('../pic/289560647_110513121705217_932356387988608571_n.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
}
</style>
</head>
<body style="font-family: verdana" >

    <style type="text/css">
        form{
            margin: auto;
            border: solid thin #aaa;
            padding: 6px;
            max-width: 200px;
            margin-top: 300px;;
            
            background-color: white;
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
    <form method="post">
        <div><?php
            if(isset($Error) && $Error !="")
            {
                echo $Error;
            }
        ?></div>
        <div id="title">Login</div>
        
        <input id="textbox" type="email" name="email" required> <br>
        <input id="textbox" type="password" name="password" required> <br><br> 
        <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
        <input type="submit" value="Login">
        <a href='signup.php'> <input type="button" value="Signup" href> </a>
    </form>
</body>
</html>