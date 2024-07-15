<?php

/**
 * Controller handling the 404 error page.
 */
class ErrorController extends Controller {

    /**
     * Processes the request to handle 404 errors.
     *
     * Sets the HTTP status code to 404 Not Found, sets the title in the header,
     * and renders the error view.
     *
     * @param array $parameters Request parameters passed to the controller (unused).
     * @return void
     */
    public function process(array $parameters): void
    {
        // Set HTTP status code to 404 Not Found
        header('HTTP/1.0 404 Not Found');

        // Set title for the error page
        $this->header['title'] = 'Chyba 404';

        // Set the view to render for the error page
        $this->view = 'error';
    }
}