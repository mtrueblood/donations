<?
//***************************// FORMAT FUNCTIONS//***************************//

//***************************
// Format DONATE TO BA email function
//***************************
function format_donateba($info, $format){

	//set the root
	$root = $_SERVER['DOCUMENT_ROOT'].'/';

	//grab the template content
	$template = file_get_contents('../resources/templates/donate-ba.'.$format);
			
	//replace all the tags
	$template = preg_replace('{NAME}', $info['name'], $template);
	$template = preg_replace('{AMOUNT}', $info['amount'], $template);
	$template = preg_replace('{EMAIL}', $info['email'], $template);
	$template = preg_replace('{MESSAGE}', $info['message'], $template);
		
	//return the html of the template
	return $template;
}

//***************************
// Format DONATE TO USER email function
//***************************
function format_donateuser($info, $format){

	//set the root
	$root = $_SERVER['DOCUMENT_ROOT'].'/';

	//grab the template content
	$template = file_get_contents('../resources/templates/donate-user.'.$format);
			
	//replace all the tags
	$template = preg_replace('{NAME}', $info['name'], $template);
	$template = preg_replace('{AMOUNT}', $info['amount'], $template);
	$template = preg_replace('{EMAIL}', $info['email'], $template);
		
	//return the html of the template
	return $template;
}
//***************************// SEND FUNCTIONS//***************************//

//***************************
// Send DONATE TO BA email function
//***************************
function send_donateba($info){
		
	//format each email
	$body = format_donateba($info,'php');
	$body_plain_txt = format_donateba($info,'txt');

	//setup the mailer
	$transport = Swift_MailTransport::newInstance();
	$mailer = Swift_Mailer::newInstance($transport);
	$message = Swift_Message::newInstance();
	$message ->setSubject('New Donation!');
	$message ->setFrom(array('noreply@company.com' => 'Company Name'));
	$message ->setTo(array('email@web.com' => 'email@web.com'));
	$message ->setBody($body_plain_txt);
	$message ->addPart($body, 'text/html');
	$result = $mailer->send($message);
	return $result;
}

//***************************
// Send DONATE TO BA email function
//***************************
function send_donateuser($info){
		
	//format each email
	$body = format_donateuser($info,'php');
	$body_plain_txt = format_donateuser($info,'txt');

	//setup the mailer
	$transport = Swift_MailTransport::newInstance();
	$mailer = Swift_Mailer::newInstance($transport);
	$message = Swift_Message::newInstance();
	$message ->setSubject('New Donation!');
	$message ->setFrom(array('noreply@company.com' => 'Company Name'));
	$message ->setTo(array($info['email'] => $info['email']));
	$message ->setBody($body_plain_txt);
	$message ->addPart($body, 'text/html');
	$result = $mailer->send($message);
	return $result;
}

//***************************
//cleanup the errors
//***************************
function show_errors($action){
	$error = false;
	if(!empty($action['result'])){
		$error = "<ul class=\"alert $action[result]\">"."\n";
		if(is_array($action['text'])){

			//loop out each error
			foreach($action['text'] as $text){
				$error .= "<li><p>$text</p></li>"."\n";
			}	
		}else{
			//single error
			$error .= "<li><p>$action[text]</p></li>";
		}
		$error .= "</ul>"."\n";
	}
	return $error;
}

?>