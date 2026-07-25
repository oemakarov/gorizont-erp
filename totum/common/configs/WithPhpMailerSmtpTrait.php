<?php


namespace totum\common\configs;

use PHPMailer\PHPMailer\PHPMailer;
use totum\common\errorException;

trait WithPhpMailerSmtpTrait
{
    abstract protected function getDefaultSender();

    public function sendMail($to, $title, $body, $attachments = [], $from = null, $replyTo = null, $hcopy = null)
    {
        list($body, $attachments) = $this->mailBodyAttachments($body, $attachments);

        try {
            $mail = new PHPMailer(true);

            $mail->SMTPDebug = $this->env !== static::ENV_LEVELS["production"];
            $mail->isSMTP();

            if (!property_exists($this, 'SmtpData') || empty($this->SmtpData) || empty($this->SmtpData['host'] || empty($this->SmtpData['port']))){
                throw new errorException($this->translate('Fill in the parameter [[%s]]', 'Conf.php SmtpData'));
            }


            $mail->Host = $this->SmtpData['host'];
            $mail->Port = $this->SmtpData['port'];

            if ($mail->SMTPAuth = !empty($this->SmtpData['login'])) {
                $mail->Username = $this->SmtpData['login'];
                $mail->Password = $this->SmtpData['pass'] ?? $this->SmtpData['password'] ?? '';
            }
            $mail->CharSet = 'utf-8';

            $from = $from ?? $this->getDefaultSender();
            //Recipients

            foreach ($this->SmtpData as $k=>$v){
                if (str_starts_with($k, 'mail_')){
                    $param = substr($k, 5);
                    $mail->$param = $v;
                }
            }


            $mail->setFrom($from, $from);
            foreach ((array)$to as $recipient) {
                $mail->addAddress($recipient);     // Add a recipient
            }

            if ($replyTo) {
                $mail->addReplyTo($replyTo);
            }
            if ($hcopy) {
                foreach ((array) $hcopy as $bccRecipient){
                    $mail->addBCC($bccRecipient);
                }
            }

            foreach ($attachments as $attachmentName => $fileString) {
                if (preg_match('/jpg|gif|png$/', $attachmentName)) {
                    $mail->addStringEmbeddedImage($fileString, $attachmentName, $attachmentName);
                } else {
                    $mail->addStringAttachment($fileString, $attachmentName);
                }
            }
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $title;
            $mail->Body = $body;


            return $mail->send();


        } catch (\Exception $e) {
            throw new \ErrorException($mail->ErrorInfo);
        }
    }
}
