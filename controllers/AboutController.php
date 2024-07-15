<?php

/**
 * Controller for displaying the "About" page.
 */
class AboutController extends Controller
{
    /**
     * Process the request.
     *
     * This method sets the page header with title, keywords, and description.
     * Then it sets the view to be displayed as "about".
     *
     * @param array $parameters Request parameters (not used in this case).
     * @return void
     */
    public function process(array $parameters): void {
        $this->header = array(
            'title' => 'O aplikaci',
            'keywords' => 'aplikace, pojištění, evidence',
            'description' => 'O aplikaci "Evidence pojištěných'
        );

        $this->view = 'about';
    }
}