<?php

/**
 * Controller handling operations related to persons insured under various insurance types.
 */
class PersonController extends Controller
{
    /**
     * Processes the request based on the provided parameters.
     *
     * If a specific person ID is provided, retrieves details of that insured person
     * and prepares data for rendering their details view.
     * If the 'odstranit' parameter is provided, deletes the insured person and redirects
     * back to the person listing page.
     * If no specific ID is provided, retrieves all insured persons for listing.
     *
     * @param array $parameters Request parameters passed to the controller.
     * @return void
     */
    public function process(array $parameters): void
    {
        $insuredManager = new InsuredManager();

        if (!empty($parameters[1]) && $parameters[1] == 'odstranit') {
            // Delete the insured person based on the provided ID
            $insuredManager->deleteInsuredPerson($parameters[0]);
            $this->addMessage('Osoba byla úspěšně odstraněna');
            $this->redirect('person');
        }
        else if (!empty($parameters[0])) {
            // Retrieve details of the insured person based on the provided ID
            $person = $insuredManager->getInsuredPerson($parameters[0]);

            // Redirect to error page if person details are not found
            if (!$person)
                $this->redirect('error');

            // Set header metadata for the person detail page
            $this->header = array(
                'title' => 'Pojištěnec',
                'keywords' => 'pojištění', 'evidence', 'pojištěnec', 'pojištěná osoba',
                'description' => 'Detail pojištěné osoby.',
            );

            // Set specific data for rendering the person detail view
            $this->data['title'] = $person['name'] . ' ' . $person['surname'];
            $this->data['name'] = $person['name'];
            $this->data['surname'] = $person['surname'];
            $this->data['identification_number'] = $person['identification_number'];
            $this->data['adress'] = $person['adress'];
            $this->data['email'] = $person['email'];
            $this->data['phone_number'] = $person['phone_number'];
            $this->data['type_of_insurance'] = $person['type_of_insurance'];
            $this->data['insurance_url'] = $person['insurance_url'];

            // Set the view to render the person detail view
            $this->view = 'person';
        } else {
            // Retrieve all insured persons for listing
            $people = $insuredManager->getAllInsured();

            // Set data for rendering the list of insured persons view
            $this->data['people'] = $people;

            // Set the view to render the list of insured persons view
            $this->view = 'people';

        }
    }
}