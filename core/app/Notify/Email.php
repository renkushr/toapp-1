<?php

namespace App\Notify;
use App\Notify\NotifyProcess;
use App\Notify\Notifiable;
use Mailjet\Client;
use Mailjet\Resources;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use SendGrid;
use SendGrid\Mail\Mail;

class Email extends NotifyProcess implements Notifiable{

    /**
    * Email of receiver
    *
    * @var string
    */
	public $email;

    /**
    * Assign value to properties
    *
    * @return void
    */
	public function __construct(){
		$this->statusField = 'email_status';
		$this->body = 'email_body';
		$this->globalTemplate = 'email_template';
		$this->notifyConfig = 'mail_config';
	}

    /**
    * Send notification
    *
    * @return void|bool
    */
	public function send(){

		//get message from parent
		$message = $this->getMessage();
		if (gs('en') && $message) {
			//Send mail
			$methodName = gs('mail_config')->name;
			$method = $this->mailMethods($methodName);
			try{
				$this->$method();
				$this->createLog('email');
			}catch(\Exception $e){
				$this->createErrorLog($e->getMessage());
				session()->flash('mail_error',$e->getMessage());
			}
		}

	}

    /**
    * Get the method name
    *
    * @return string
    */
	protected function mailMethods($name){
		$methods = [
			'php'=>'sendPhpMail',
			'smtp'=>'sendSmtpMail',
			'sendgrid'=>'sendSendGridMail',
			'mailjet'=>'sendMailjetMail',
		];
		return $methods[$name];
	}

	protected function sendPhpMail(){
        $sentFromName = $this->getEmailFrom()['name'];
        $sentFromEmail = $this->getEmailFrom()['email'];
		$headers = "From: $sentFromName <$sentFromEmail> \r\n";
	    $headers .= "From: $sentFromName <$sentFromEmail> \r\n";
	    $headers .= "MIME-Version: 1.0\r\n";
	    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
	    @mail($this->email, $this->subject, $this->finalMessage, $headers);
	}

	protected function sendSmtpMail(){
		$mail = new PHPMailer(true);
		$config = gs('mail_config');
        $from = $this->getEmailFrom();
        //Server settings
        $mail->isSMTP();
        $mail->Host       = $config->host;
        $mail->SMTPAuth   = !empty($config->username);
        $mail->Username   = $config->username;
        // Strip spaces/NBSPs that may remain from older saved Gmail App Passwords.
        $mail->Password   = preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{202F}\x{205F}\x{3000}]+/u', '', (string) ($config->password ?? ''));
        if ($config->enc == 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($config->enc == 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPAutoTLS = false;
            $mail->SMTPSecure = false;
        }
        $mail->Port       = (int) $config->port;
        $mail->CharSet = 'UTF-8';
        //Recipients
        if (
            str_contains(strtolower((string) $config->host), 'gmail.com')
            && !empty($config->username)
            && strcasecmp((string) $from['email'], (string) $config->username) !== 0
        ) {
            $from['email'] = $config->username;
        }
        $mail->setFrom($from['email'], $from['name']);
        $mail->addAddress($this->email, $this->receiverName);
        $mail->addReplyTo($from['email'], $from['name']);
        // Content
        $mail->isHTML(true);
        $mail->Subject = $this->subject;
        $mail->Body    = $this->finalMessage;
        $mail->send();
	}

	protected function sendSendGridMail(){
		$sendgridMail = new Mail();
	    $sendgridMail->setFrom($this->getEmailFrom()['email'], $this->getEmailFrom()['name']);
	    $sendgridMail->setSubject($this->subject);
	    $sendgridMail->addTo($this->email, $this->receiverName);
	    $sendgridMail->addContent("text/html", $this->finalMessage);
	    $sendgrid = new SendGrid(gs('mail_config')->appkey);
	    $response = $sendgrid->send($sendgridMail);
	    if($response->statusCode() != 202){
	    	throw new Exception(json_decode($response->body())->errors[0]->message);

	    }
	}

	protected function sendMailjetMail()
	{
	    $mj = new Client(gs('mail_config')->public_key, gs('mail_config')->secret_key, true, ['version' => 'v3.1']);
	    $body = [
	        'Messages' => [
	            [
	                'From' => [
	                    'Email' => $this->getEmailFrom()['email'],
	                    'Name' => $this->getEmailFrom()['name'],
	                ],
	                'To' => [
	                    [
	                        'Email' => $this->email,
	                        'Name' => $this->receiverName,
	                    ]
	                ],
	                'Subject' => $this->subject,
	                'TextPart' => "",
	                'HTMLPart' => $this->finalMessage,
	            ]
	        ]
	    ];
	    $response = $mj->post(Resources::$Email, ['body' => $body]);
	}

    /**
    * Configure some properties
    *
    * @return void
    */
	public function prevConfiguration(){
		
		if ($this->user) {
			$this->email = $this->user->email;
			$this->receiverName = $this->user->fullname;
		}
		$this->toAddress = $this->email;
	}

    private function getEmailFrom(){
        // Prefer global Email settings — SendGrid/Mailjet reject unverified template From addresses.
        $globalFrom = gs('email_from');
        $method = gs('mail_config')->name ?? '';
        if (in_array($method, ['sendgrid', 'mailjet', 'smtp'], true) && !empty($globalFrom)) {
            $this->sentFrom = $globalFrom;
        } else {
            $this->sentFrom = $this->template->email_sent_from_address ?? $globalFrom;
        }

        $fromName = gs('email_from_name') ?: gs('site_name');
        $templateName = $this->template->email_sent_from_name ?? null;
        // Ignore stale template names that look like email addresses (e.g. no-reply@...).
        if (!empty($templateName) && !str_contains((string) $templateName, '@') && empty(gs('email_from_name'))) {
            $fromName = $templateName;
        }

        return [
            'email' => $this->sentFrom,
            'name' => $this->replaceTemplateShortCode($fromName),
        ];
    }
}
