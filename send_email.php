<?php
//Email to send enrolment too
define("EMAIL", "enrolmentoffice@localhost");
//SMTP authenticated address to send emails from
define("FROM", "webadmin@localhost");

$auto_reply = FALSE;

if(isset($_POST['submit']) && !empty($_POST['submit'])):
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])):
        //your site secret key, 'keep it secret, keep it safe
        $secret = 'KeyGoesHere'; //Change me
        //get verify response data
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if($responseData->success):

            //contact form submission code
            $_POST['first_name'] = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
            $_POST['last_name'] = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
            $_POST['address'] = filter_var($_POST['address'],  FILTER_SANITIZE_STRING);
            $_POST['city'] = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
            $_POST['postcode'] = filter_var($_POST['postcode'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['phone'] = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $_POST['ew_id'] = filter_var($_POST['ew_id'], FILTER_SANITIZE_STRING);
            $_POST['ew_reg'] = filter_var($_POST['ew_reg'], FILTER_SANITIZE_STRING);
            $_POST['reg_type'] = filter_var($_POST['reg_type'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['course'] = filter_var($_POST['course'], FILTER_SANITIZE_STRING);
            $_POST['ewrb_complete'] = filter_var($_POST['ewrb_complete'], FILTER_SANITIZE_STRING);


            $first_name = !empty($_POST['first_name'])?$_POST['first_name']:'';
            $last_name = !empty($_POST['last_name'])?$_POST['last_name']:'';
            $address = !empty($_POST['address'])?$_POST['address']:'';
            $city = !empty($_POST['city'])?$_POST['city']:'';
            $postcode = !empty($_POST['postcode'])?$_POST['postcode']:'';
            $phone = !empty($_POST['phone'])?$_POST['phone']:'';
            $email = !empty($_POST['email'])?$_POST['email']:'';
            $ew_id = !empty($_POST['ew_id'])?$_POST['ew_id']:'';
            $ew_reg = !empty($_POST['ew_reg'])?$_POST['ew_reg']:'';
            $reg_type = !empty($_POST['reg_type'])?$_POST['reg_type']:'';
            $course = !empty($_POST['course'])?$_POST['course']:'';
            $ewrb_complete = !empty($_POST['ewrb_complete'])?$_POST['ewrb_complete']:'';


            //$subject = 'New contact form have been submitted';
						$subject = "New enrolment from: " . $first_name;
            $htmlContent = "
                <h1>Enrolment details</h1>
                <p><b>first_name: </b>".$first_name."</p>
                <p><b>last_name: </b>".$last_name."</p>
                <p><b>address: </b>".$address."</p>
                <p><b>city: </b>".$city."</p>
                <p><b>postcode: </b>".$postcode."</p>
                <p><b>phone: </b>".$phone."</p>
                <p><b>email: </b>".$email."</p>
                <p><b>ew_id: </b>".$ew_id."</p>
                <p><b>ew_reg: </b>".$ew_reg."</p>
                <p><b>reg_type: </b>".$reg_type."</p>
                <p><b>course: </b>".$course."</p>
                <p><b>e-learning status: </b>".$ewrb_complete."</p>

            ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            // More headers
            $headers .= "From: Vidtech Services <". FROM .">\r\n";
            //send email
            @mail(EMAIL,$subject,$htmlContent,$headers);

            $succMsg = 'Your contact request have submitted successfully.';
        else:
            $errMsg = 'Robot verification failed, please try again.';
        endif;
    else:
        $errMsg = 'Please click on the reCAPTCHA box.';
    endif;
else:
    $errMsg = '';
    $succMsg = '';
endif;
/*
if($succMsg != null):
    header("Location: /training/success/");
endif;
if($errMsg != null):
    header("Location: /training/retry/");
endif;
*/
?>
