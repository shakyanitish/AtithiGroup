<?php
require_once("includes/initialize.php");
$usermail = User::get_UseremailAddress_byId(1);
$ccusermail = User::field_by_id(1, 'optional_email');
$sitename = Config::getField('sitename', true);

$recaptcha_secret = '6LdR7FosAAAAANDYpKT5XbG-8TXXHMaGvQzYkIz6';

foreach ($_POST as $key => $val) {
  $$key = $val;
}


if ($_POST['action'] == "forContact"):
    //--------------------------
    function verifyRecaptcha($response, $secret) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secret,
        'response' => $response
    );

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        return false; // Failed to communicate with Google API
    }
    
    $json_result = json_decode($result, true);
    
    // Check the 'success' key from Google's response
    return isset($json_result['success']) && $json_result['success'] == true;
}

    // Verify reCAPTCHA first
    $recaptcha_response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
    if (empty($recaptcha_response) || !verifyRecaptcha($recaptcha_response, $recaptcha_secret)) {
        echo json_encode(array("action" => "error", "message" => "reCAPTCHA verification failed. Please try again."));
        exit;
    }

    //-------------------------
    $body = '
        <table width="100%" border="0" cellpadding="0" style="font:12px Arial, serif;color:#222;">
            <tr>
                <td><p>Dear Sir,</p></td>
            </tr>
            <tr>
                <td>
                    <p>
                        <span style="color:#0065B3; font-size:14px; font-weight:bold">
                        Contact message</span><br />
                        The details provided are:
                    </p>
                    <p>
                        <strong>Name</strong> : ' . $fullname . '<br />		
                        <strong>E-mail Address</strong>: ' . $email . '<br />
                        <strong>Phone</strong>: ' . $phone . '<br />
                        <strong>Address</strong>: ' . $address . '<br />
                        <strong>Message</strong>: ' . $message . '<br />

                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Thank you,<br />
                    ' . $fullname . '
                    </p>
                </td>
            </tr>
        </table>
  ';

    $mail = new PHPMailer();
    $mail->SetFrom($email, $fullname);
    $mail->AddReplyTo($email, $fullname);
    $mail->AddAddress($usermail, $sitename);
    if (!empty($ccusermail)) {
        $rec = explode(';', $ccusermail);
        if ($rec) {
            foreach ($rec as $row) {
                $mail->AddCC($row, $sitename);
            }
        }
    }

    $mail->Subject = 'Enquiry Contact mail from ' . $fullname . '';
    $mail->MsgHTML($body);
  
    if (!$mail->Send()) {
        echo json_encode(array("action" => "unsuccess", "message" => "We could not sent your message at the time. Please try again later."));
    } else {
        echo json_encode(array("action" => "success", "message" => "Your message has been successfully sent."));
    }
  endif;
  


//blog***************************************************************************************
if ($_POST['action'] == "forBlog"):

        $Blogs = Blog::get_allblog();
        //pr($Blogs);
         foreach ($Blogs as $homebl) {
            
           if(!empty($homebl->linksrc)){
            // $pagelink = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
            $linkTarget = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
                $linksrc = ($homebl->linktype == 1) ? $homebl->linksrc : BASE_URL.$homebl->linksrc;
           }
           else{
                $linksrc= BASE_URL. 'blog/'. $homebl->slug;
           }
        }
    $body = '
        <table width="100%" border="0" cellpadding="0" style="font:12px Arial, serif;color:#222;">
            <tr>
                <td><p>Dear Sir,</p></td>
            </tr>
            <tr>
                <td>
                    <p>
                        <span style="color:#0065B3; font-size:14px; font-weight:bold">Comment message</span><br />
                        The details provided are:
                    </p>
                    <p>
                        <strong>Name</strong> : ' . $name . '<br />		
                        <strong>E-mail Address</strong>: ' . $email . '<br />
                        <strong>Comment</strong>: ' . $message . '<br />

                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Thank you,<br />
                    ' . $name . '
                    </p>
                </td>
            </tr>
        </table>
  ';

    $mail = new PHPMailer();
    $mail->SetFrom($email, $name);
    $mail->AddReplyTo($email, $name);
    $mail->AddAddress($usermail, $sitename);
    if (!empty($ccusermail)) {
        $rec = explode(';', $ccusermail);
        if ($rec) {
            foreach ($rec as $row) {
                $mail->AddCC($row, $sitename);
            }
        }
    }

  
    $mail->Subject = 'Enquiry Blog mail from ' . $homebl->title . '';
    $mail->MsgHTML($body);
  
    if (!$mail->Send()) {
        echo json_encode(array("action" => "unsuccess", "message" => "We could not sent your message at the time. Please try again later."));
    } else {
        echo json_encode(array("action" => "success", "message" => "Your message has been successfully sent."));
    }
  endif;

//hall***************************************************************************************************
if ($_POST['action'] == "forHall"):
  $body = '
      <table width="100%" border="0" cellpadding="0" style="font:12px Arial, serif;color:#222;">
          <tr>
              <td><p>Dear Sir,</p></td>
          </tr>
          <tr>
              <td>
                  <p>
                      <span style="color:#0065B3; font-size:14px; font-weight:bold">Online Reservation Inquiry message</span><br />
                      The details provided are:
                  </p>
                  <p>
                      <strong>Event Date</strong> : ' . $event_date . '<br />		
                      <strong>Pax</strong> : ' . $pax . '<br />		
                      <strong>Event Time</strong> : ' . $event_time . '<br />		
  ';
  if (!empty($rooms)) {
      $body .= '<strong>Rooms for the Event?</strong> : ' . $rooms . ' <br />';
  }
  $body .= '
                      <strong>Name</strong> : ' . $name . '<br />		
                      <strong>E-mail Address</strong>: ' . $email . '<br />
                      <strong>Phone</strong>: ' . $phone . '<br />
                  </p>
              </td>
          </tr>
          <tr>
              <td>
                  <p>Thank you,<br />
                  ' . $name . '
                  </p>
              </td>
          </tr>
      </table>
';

  $mail = new PHPMailer();
  $mail->SetFrom($email, $name);
  $mail->AddReplyTo($email, $name);
  $mail->AddAddress($usermail, $sitename);
  if (!empty($ccusermail)) {
      $rec = explode(';', $ccusermail);
      if ($rec) {
          foreach ($rec as $row) {
              $mail->AddCC($row, $sitename);
          }
      }
  }

  $mail->Subject = 'Online Reservation Inquiry mail from ' . $name;
  $mail->MsgHTML($body);

  if (!$mail->Send()) {
      echo json_encode(array("action" => "unsuccess", "message" => "We could not sent your Inquiry at the time. Please try again later."));
  } else {
      echo json_encode(array("action" => "success", "message" => "Your Inquiry has been successfully sent."));
  }
endif;


?>