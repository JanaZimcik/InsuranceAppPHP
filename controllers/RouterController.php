<?php

/**
 * Controller responsible for routing requests to appropriate controllers based on the URL.
 */
class RouterController extends Controller {
    protected Controller $controller;

    /**
     * Processes the incoming request based on the provided parameters.
     *
     * Parses the URL to determine the controller and action to be executed.
     * Instantiates the appropriate controller class based on the URL parameters.
     * Sets the header metadata and view for rendering the response.
     * Retrieves any messages to be displayed in the layout.
     *
     * @param array $parameters Request parameters passed to the router.
     * @return void
     */
    public function process(array $parameters): void
    {
        // Parse the URL to extract controller and action
        $parsedURL = $this->parseURL($parameters[0]);

        // Redirect to the 'about' page if no specific controller is provided
        if (empty($parsedURL[0]))
            $this->redirect('about');

        // Convert URL segments into camelCase format to find controller class
        $controllerClass = $this->dashesIntoCamelCase(array_shift($parsedURL)) . 'Controller';

        // Instantiate the controller class if it exists; otherwise, redirect to error page
        if (file_exists('controllers/' . $controllerClass . '.php'))
            $this->controller = new $controllerClass();
        else
            $this->redirect('error');

        // Process the request using the instantiated controller
        $this->controller->process($parsedURL);

        // Set header metadata from the processed controller
        $this->data['title'] = $this->controller->header['title'];
        $this->data['description'] = $this->controller->header['description'];
        $this->data['keywords'] = $this->controller->header['keywords'];

        // Set the view to render the layout
        $this->view = 'layout';

        // Retrieve messages to be displayed in the layout
        $this->data['messages'] = $this->getMessages();

    }

    /**
     * Parses the URL into an array of segments.
     *
     * @param string $url The URL to parse.
     * @return array An array of URL segments.
     */
    private function parseURL(string $url): array{
        // Parse the URL and clean up the path
        $parsedURL = parse_url($url);
        $parsedURL["path"] = ltrim($parsedURL["path"], "/");
        $parsedURL["path"] = trim($parsedURL["path"]);
        $dividedPath = explode("/", $parsedURL["path"]);

        return $dividedPath;
    }

    /**
     * Converts dashed string into camelCase format.
     *
     * @param string $text The text to convert.
     * @return string Converted camelCase string.
     */
    private function dashesIntoCamelCase(string $text): string{
        // Replace dashes with spaces, capitalize each word, and remove spaces
        $sentence = str_replace('-', ' ', $text);
        $sentence = ucwords($sentence);
        $sentence = str_replace(' ', '', $sentence);

        return $sentence;
    }
}