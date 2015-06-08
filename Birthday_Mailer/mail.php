<?php
error_reporting(1);
require 'PHPMailerAutoload.php';
date_default_timezone_set('Asia/Kolkata');
 
$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'mymail@mymail.com';                   // SMTP username
$mail->Password = 'mypass';               // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
$mail->Port = 587;                                    //Set
$conn = mysqli_connect('localhost', 'root', '','bdb');


if (!$conn) {
    die('Invalid conn: ' . $result);
}
$sql="select  senton,gender, date_format(senton,'%Y') as myear,name, mail, date_format(dob,'%Y') as age from birthday where day(dob) = DAYOFMONTH(now()) and month(dob) = month(now())";

$result=mysqli_query($conn,$sql);

if (!$result) {
    die('Invalid query: ' . $result);
}
else 
   {
    while($userData = $result->fetch_assoc())
    {          
                $year_birth=$userData['age'];
                $age=date("Y")-$year_birth;
                $datentime=date('Y-m-d');
                $sqlupdat='update birthday set senton="'.$datentime.'" where name ="'.$userData['name'].'"';
                //echo($sqlupdat);
                $result_update=mysqli_query($conn,$sqlupdat);
               
                if(!$result_update) {
                die('Invalid query in update: ');
                }
                $datentime=date('Y-m-d H:i:s');
                $sqllog='insert into `bdaylog` (mail_log, senton_log, comments)values("'.$userData['mail'].'","'.$datentime.'","no comments")';
                
                $resultlog=mysqli_query($conn,$sqllog);
               
                if (!$resultlog) {
                die('Invalid query in log: ' . $resultlog);
                }
                //string(substr($age,-1))
                switch(substr($age,-1))
                {     case "1": $age_ext= "st";
                          break;
                      case "2": $age_ext= "nd";
                          break;
                      case "3": $age_ext= "rd";
                          break;
                default: $age_ext="th";
                }
               
            $name=$userData['name'];
            $to=$userData['mail'];
            $mail->isHTML(true); 
            $mail->setFrom('birthdays.trendin@gmail.com', 'Team Trendin.');     //Set who the message is to be sent from
            $mail->addReplyTo('varshucop@gmail.com', 'First Last');  //Set an alternative reply-to address
            $mail->addAddress($to, $name);  // Add a recipient
            $mail->Subject = 'Happy Birthday:)';
            $gen=$userData['gender'];
            if($gen=='m'){
				$mail->AddEmbeddedImage("uploads/bdbm.jpg", "my-attach", "uploads/bdb.jpg");	
			}else{
				$mail->AddEmbeddedImage("uploads/bdbf.jpg", "my-attach", "uploads/bdb.jpg");	
			}
            
            $mail->Body = 'Hi <b>'.$name.'</b> Wish you a happy and prosperous '.$age.$age_ext.' Birthday.';
            $mail->WordWrap = 70;
            $mail->AltBody = 'Happy Birthday:)';
            if(!$mail->send()) {
                echo 'Message could not be sent.';
              //  echo 'Mailer Error: ' . $mail->ErrorInfo;
                exit;
            }else{
        echo 'Message has been sent';}
    }
}
?>
