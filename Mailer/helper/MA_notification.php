<?php

/*
 * @author      Ulrich Bittner
 * @copyright   (c) 2021
 * @license    	CC BY-NC-SA 4.0
 * @see         https://github.com/ubittner/Mailer/tree/main/Mailer
 */

/** @noinspection PhpUnused */

declare(strict_types=1);

trait MA_notification
{
    public function SendMessage(string $Subject, string $Text): bool
    {
        if (!$this->CheckInstance()) {
            return false;
        }
        $recipients = json_decode($this->ReadPropertyString('Recipients'));
        if (empty($recipients)) {
            return false;
        }
        $result = true;
        foreach ($recipients as $recipient) {
            if (!$recipient->Use) {
                continue;
            }
            $result = $this->SendData($Subject, $Text, $recipient->Address);
            if (!$result) {
                $result = false;
            }
        }
        return $result;
    }

    public function SendMessageEx(string $Subject, string $Text, string $Address): bool
    {
        if (!$this->CheckInstance()) {
            return false;
        }
        return $this->SendData($Subject, $Text, $Address);
    }

    #################### Private

    private function SendData(string $Subject, string $Text, string $Address): bool
    {
        if (!$this->CheckInstance()) {
            return false;
        }
        $id = $this->ReadPropertyInteger('SMTP');
        if ($id == 0 || @!IPS_ObjectExists($id)) {
            return false;
        }
        if (empty($Subject)) {
            return false;
        }
        if (empty($Text)) {
            return false;
        }
        if (empty($Address) || strlen($Address) <= 3) {
            return false;
        }
        return @SMTP_SendMailEx($id, $Address, $Subject, $Text);
    }
}