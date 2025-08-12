<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



function send_email($to,$subject,$fullName,$body,$maill){
    //   require 'phpmailer/vendor/autoload.php';
        $mail = $maill;       
        
    try {        
            $mail->isSMTP();
            
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

            $mail->Host = gethostname();  
          
            $mail->Port = 587;
            
            $mail->SMTPSecure = 'tls';

            $mail->SMTPAuth = true;

           $mail->Username = 'no-reply@starnetweb.com';

            $mail->Password = 'TC,E!AY-cfh(';

            $mail->setFrom('no-reply@starnetweb.com','Starnet');
            $mail->addAddress($to, $fullName);     //Add a recipient 
            
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send(); 

           
             
    
    } 
    catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function upload_file($file,$allowed_extensions,$user_id){ 
  
  require 'database.php';
  require '../config.php';
      $file_name = $file['name'];
       $file_temp_name = $file['tmp_name'];
       $file_size = $file['size'];
       $file_error = $file['error'];
       $file_type = $file['type'];        
       $file_ext = explode('.',$file_name);   
       
       $file_actual_ext = strtolower(end($file_ext));
       $allowed_files = $allowed_extensions;
  if (in_array($file_actual_ext,$allowed_files)) {
           if ($file_error === 0 ) {
               if ($file_size < 2000000) {
                   $file_new_name = uniqid('',true).'.'.$file_actual_ext;                                    
                   $file_dest = ROOT_FOLDER.$file_new_name;
                   move_uploaded_file($file_temp_name,$file_dest);        
                                           
                   $update_sql = 'UPDATE user SET dp = :file_new_name Where id = :id LIMIT 1';
                    $update = $pdo->prepare($update_sql);        
                    $update->execute(['file_new_name' => $file_new_name,'id' => $user_id]);
                    header('location:account-settings.php?status=updated');
                    
               }
               else {
                  $error ="Choose a file less than 1MB!"; 
                                  
               }
           }
           else {               
               $error = "There was an error uploading your file!";
           }
       }
       else {       
           $error ="you cannot upload files of this type!";
       }
       
}

function generateUniqueCode($pdo, $words) {
    $prefix = getInitials($words);
    
    do {
        $number = rand(10000, 99999);
        $code = $prefix .'-'.$number;

        // Check if it exists in the database
        $stmtGet = $pdo->prepare("SELECT COUNT(*) FROM socialorder WHERE id = :id");
        $stmtGet->execute(['id' => $code]);
        $count = $stmtGet->fetchColumn();
        
    } while ($count > 0); // Repeat until a unique code is found

    return $code;
}
function generateUniqueCodeTask($pdo, $words) {
    $prefix = getInitials($words);
    
    do {
        $number = rand(10000, 99999);
        $code = $prefix .'-'.$number;

        // Check if it exists in the database
        $stmtGet = $pdo->prepare("SELECT COUNT(*) FROM task WHERE id = :id");
        $stmtGet->execute(['id' => $code]);
        $count = $stmtGet->fetchColumn();
        
    } while ($count > 0); // Repeat until a unique code is found

    return $code;
}


function getInitials($string) {
    // Break the string into words
    $words = explode(' ', trim($string));
    $initials = '';

    foreach ($words as $word) {
        if (!empty($word)) {
            $initials .= strtoupper($word[0]);
        }
    }

    return $initials;
}



?>