<?php

    require('../admin/inc/essentials.php');
    require('../admin/inc/db_config.php');
    // require('connection.php');

    // use PHPMailer\PHPMailer\PHPMailer;
    // use PHPMailer\PHPMailer\SMTP;
    // use PHPMailer\PHPMailer\Exception;

    // function sendMail($email,$v_code)
    // {
    //     require '../emailverify/phpmailer/PHPMailer.php';
    //     require '../emailverify/phpmailer/SMTP.php';
    //     require '../emailverify/phpmailer/Exception.php';

    //     $mail = new PHPMailer(true);

    //     try {
    //         $mail->isSMTP();                                            //Send using SMTP
    //         $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    //         $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    //         $mail->Username   = 'rahuldeve97@gmail.com';                     //SMTP username
    //         $mail->Password   = '9922358154';                               //SMTP password
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        
    //         $mail->setFrom('rahuldeve97@gmail.com', 'Hotel Minerva Paradise');
    //         $mail->addAddress($email);     
        
    //         $mail->isHTML(true);                                  //Set email format to HTML
    //         $mail->Subject = 'Email Verification from Hotel Minerva Paradise!';
    //         $mail->Body    = "Thanks for Registration!
    //             Click the link below to verify the email address
    //             <a href='http://localhost/hbwebsite/emailverify\phpmailer/verify.php?email=$email&$v_code'>Verify</a> ";
        
    //         $mail->send();
    //         return true;
    //     } 
    //     catch (Exception $e){
    //         return false;
    //     }

    // }

    if(isset($_POST['login']))
    {
        $data = filteration($_POST);

        $u_exist = select("SELECT * FROM `user_cred` WHERE `username`=? OR `email`=? LIMIT 1",[$data['email_username'],$data['email_username']],"ss");

        if(mysqli_num_rows($u_exist)==0){
            echo 'inv_email_username';
        }
        else{
            $u_fetch = mysqli_fetch_assoc($u_exist);
            if($u_fetch['status']==0){
                echo 'inactive';
            }
            else{
                if(!password_verify($data['password'],$u_fetch['password'])){
                    echo 'inv_password';
                }
                else{
                    session_start();
                    $_SESSION['login']=true;
                    $_SESSION['uId']=$u_fetch['id'];
                    $_SESSION['uName']=$u_fetch['username'];
                    $_SESSION['uemail']=$u_fetch['email'];
                    echo 1;
                }
            }
        }
    }
        

    if(isset($_POST['register']))
    {
        $data = filteration($_POST);

        // match password and confirm password field

        // if($data['pass'] != $data['cpass']){
        //     echo 'pass_mismatch';
        //     exit;
        // }

        // Check user exists or not 

        $u_exist = select("SELECT * FROM `user_cred` WHERE `username`=? OR `email`=? LIMIT 1",[$data['username'],$data['email']],"ss");
        // $result = mysqli_query($con,$u_exist);

        if(mysqli_num_rows($u_exist)!=0){
            $u_exist_fetch = mysqli_fetch_assoc($u_exist);
            echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'username_already';
            exit;
        }
        else
        {
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $query = "INSERT INTO `user_cred`(`name`, `username`, `email`, `phonenum`, `address`, `password`) VALUES (?,?,?,?,?,?)";

            $values = [$data['fullname'],$data['username'],$data['email'],$data['phonenum'],$data['address'],$password];

            if(insert($query,$values,'ssssss'))
            {
                echo 1;
            }
            else
            {
                echo 'ins_failed';
            }
        }
    }

?>