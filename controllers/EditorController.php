<?php

/**
 * Controller handling the editing and saving of insured persons.
 */
class EditorController extends Controller
{

    /**
     * Processes the request to handle editing and saving of insured persons.
     *
     * @param array $parameters Request parameters passed to the controller.
     * @return void
     */
    function process(array $parameters): void
    {
        $this->header['title'] = "Editor pojištěných";

        // Initialize managers
        $insuredManager = new InsuredManager();
        $insuranceManager = new InsuranceManager();

        // Initialize empty person data array
        $person = array(
            'insured_id' => '',
            'name' => '',
            'surname' => '',
            'identification_number' => '',
            'adress' => '',
            'email' => '',
            'phone_number' => '',
            'insurance_fk' => '',
            'url' => '',
        );

        // Handle form submission
        if ($_POST) {
            // Define keys for person data
            $keys = array('name', 'surname', 'identification_number', 'adress', 'email', 'phone_number', 'insurance_fk', 'url');
            // Extract and filter form data based on keys
            $person = array_intersect_key($_POST, array_flip($keys));

            // Generate URL based on identification number
            if (!empty($person['identification_number'])) {
                $url = $person['identification_number'];

                $url = str_replace('/', '', $url);
                $person['url'] = $url;
            }

            // Save or update insured person data
            $insuredManager->saveInsuredPerson($_POST["insured_id"], $person);
            $this->addMessage('Osoba byla úspěšně přidána.');
            $this->redirect('person');
        } else if (!empty($parameters[0])) {
            // Load existing person data for editing
            $readPerson = $insuredManager->getInsuredPerson($parameters[0]);
            if ($readPerson) {
                $person = $readPerson;
            } else {
                $this->addMessage('Osoba nebyla nalezena.');
            }
        }

        // Retrieve all insurance types for selection
        $insuranceTypes = $insuranceManager->getAllInsuranceTypes();

        // Set data for the view
        $this->data['person'] = $person;
        $this->data['insuranceTypes'] = $insuranceTypes;

        // Set the view to be rendered
        $this->view = 'editor';
    }
}