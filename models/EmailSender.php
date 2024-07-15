<?php

/**
 * Class EmailSender
 *
 * A class responsible for sending emails with optional antispam validation.
 */
class EmailSender {

    /**
     * Sends an email using the provided parameters.
     *
     * @param string $to       The recipient's email address.
     * @param string $subject  The subject of the email.
     * @param string $message  The content of the email.
     * @param string $from     The sender's email address.
     * @throws UserError       If the email fails to send.
     */
    public function sendEmail(string $to, string $subject, string $message, string $from): void {
        $header = "From: " . $from;
        $header .= "\nMIME-Version: 1.0\n";
        $header .= "Content-Type: text/html; charset=\"utf-8\"\n";
        if (!mb_send_mail($to, $subject, $message, $header))
            throw new UserError('Email se nepodařilo odeslat.');
    }

    /**
     * Sends an email after validating an antispam condition based on the year.
     *
     * @param string $year     The antispam year to validate.
     * @param string $to       The recipient's email address.
     * @param string $subject  The subject of the email.
     * @param string $message  The content of the email.
     * @param string $from     The sender's email address.
     * @throws UserError       If the antispam validation fails or the email fails to send.
     */
    public function sendWithAntispam(string $year, string $to, string $subject, string $message, string $from): void {
        if ($year != date("Y"))
            throw new UserError('Chybně vyplněný antispam.');
        $this->sendEmail($to, $subject, $message, $from);
    }
}