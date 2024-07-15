<?php

/**
 * Abstract base class for controllers.
 */
abstract class Controller {

    /**
     * @var array Holds data to be passed to the view.
     */
    protected array $data = array();

    /**
     * @var string Represents the view file to be rendered.
     */
    protected string $view = "";

    /**
     * @var array Holds metadata for the HTML header.
     */
    protected array $header = array('title' => '', 'keywords' => '', 'description' => '');

    /**
     * Processes the request based on the provided parameters.
     * This method must be implemented in the derived classes.
     *
     * @param array $parameters Request parameters to process.
     * @return void
     */
    abstract function process(array $parameters): void;

    /**
     * Renders the view specified by $this->view and passes data to it.
     * Data is sanitized to prevent XSS attacks using htmlspecialchars.
     *
     * @return void
     */
    public function listView(): void{
        if($this->view) {
            // Sanitize data for security
            extract($this->secure($this->data));
            extract($this->data, EXTR_PREFIX_ALL, "");

            // Include the view file
            require("views/" . $this->view . ".phtml");
        }
    }

    /**
     * Redirects the user to the specified URL.
     *
     * @param string $url The URL to redirect to.
     * @return never
     */
    public function redirect(string $url): never
    {
        // Perform a redirect and terminate the script
        header("Location: /$url");
        header("Connection: close");
        exit;
    }

    /**
     * Sanitizes input data to prevent XSS attacks.
     *
     * @param mixed $x Data to be sanitized.
     * @return mixed Sanitized data.
     */
    private function secure(mixed $x = null): mixed {
        if(!isset($x))
            return null;
        elseif (is_string($x))
            return htmlspecialchars($x, ENT_QUOTES);
        elseif (is_array($x)) {
            foreach($x as $k => $v) {
                $x[$k] = $this->secure($v);
            }
            return $x;
        } else
            return $x;
    }

    /**
     * Adds a message to the session for display.
     *
     * @param string $message The message to add.
     * @return void
     */
    public function addMessage(string $message): void {
        if (isset($_SESSION['messages']))
            $_SESSION['messages'][] = $message;
        else
            $_SESSION['messages'] = array($message);
    }

    /**
     * Retrieves all messages stored in the session.
     * Clears the messages from the session after retrieval.
     *
     * @return array Array of messages.
     */
    public function getMessages(): array {
        if(isset($_SESSION['messages'])) {
            $messages = $_SESSION['messages'];
            unset($_SESSION['messages']);
            return $messages;
        } else
            return array();
    }
}