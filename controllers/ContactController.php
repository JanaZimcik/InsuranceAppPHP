<?php

/**
 * Controller handling the contact form functionality.
 */
class ContactController extends Controller {

    /**
     * Process the request.
     *
     * This method sets the page header with title, keywords, and description for the contact page.
     * If a POST request is detected, it attempts to send an email using EmailSender class with anti-spam protection.
     * Messages are added based on success or failure of the email sending process.
     *
     * @param array $parameters Request parameters (not used in this case).
     * @return void
     */
    public function process(array $parameters): void {
        $this->header = array(
            'title' => 'Kontaktní formulář',
            'keywords' => 'kontakt, email, fomrulář',
            'description' => 'Kontaktní formulář našeho webu.'
        );

        // Process POST request for sending email
        if ($_POST) {
            try {
                $emailSender = new EmailSender();
                // Send email with anti-spam protection
                $emailSender->sendWithAntispam($_POST['year'], "jana.zimcik@gmail.com", "Email z webu", $_POST['message'], $_POST['email']);
                $this->addMessage('Email byl úspěšně odeslán.');
                $this->redirect('contact');
            } catch (UserError $error) {
                $this->addMessage($error->getMessage());
            }
        }

        // Set the view to be displayed
        $this->view = 'contact';
    }
}