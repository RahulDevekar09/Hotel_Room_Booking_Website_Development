<?php

    require('../admin/inc/essentials.php');
    require('../admin/inc/db_config.php');

    date_default_timezone_set("Asia/Kolkata");

    if(isset($_POST['check_availability'])){
        $frm_data = filteration($_POST);
        $status = "";
        $result = "";

        // check in and out validation

        $today_date = new DateTime(date("Y-m-d"));
        $checkin_date = new DateTime($frm_data['check_in']);
        $checkout_date = new DateTime($frm_data['check_out']);

        if($checkin_date == $checkout_date){
            $status = 'check_in_out_equal';
            $result = json_encode(["status"=>$status]);
        }
        else if($checkout_date < $checkin_date){
            $status = 'check_out_earlier';
            $result = json_encode(["status"=>$status]);
        }
        else if($checkin_date < $today_date){
            $status = 'check_in_earlier';
            $result = json_encode(["status"=>$status]);
        }

        // check booking availability if status is blank else return the error

        if($status!=''){
            echo $result;
        }
        else{
            session_start();
            $_SESSION['room'];

            // run query to check room is available or not

            $count_days = date_diff($checkin_date,$checkout_date)->days;
            $payment = $_SESSION['room']['price'] * $count_days;
            
            $_SESSION['room']['payment'] = $payment;
            $_SESSION['room']['available'] = true;

            $result = json_encode(["status"=>"available", "days"=>$count_days, "payment"=>$payment]);
            echo $result;
        }

    }
?>