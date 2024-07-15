<?php

/**
 * Controller handling operations related to insurance entities.
 */
class InsuranceController extends Controller
{

    /**
     * Processes the request based on the provided parameters.
     *
     * If a specific insurance ID is provided in the parameters, retrieves
     * details of that insurance and prepares data for rendering its view.
     * If no specific ID is provided, retrieves all insurance types for listing.
     *
     * @param array $parameters Request parameters passed to the controller.
     * @return void
     */
    function process(array $parameters): void
    {
        $insuranceManager = new InsuranceManager();

        if (!empty($parameters[0])) {
            // Retrieve insurance details for a specific ID
            $insurance = $insuranceManager->getInsurance($parameters[0]);

            // Redirect to error page if insurance details are not found
            if (!$insurance)
                $this->redirect('error');

            // Set header metadata for the insurance page
            $this->header = array(
                'title' => $insurance['type_of_insurance'],
                'keywords' => $insurance['keywords'],
                'description' => $insurance['description'],
            );

            // Set specific data for the insurance view
            $this->data['title'] = $insurance['type_of_insurance'];
            $this->data['insurance'] = $insurance;

            // Set the view to render the insurance details
            $this->view = 'insurance';

        } else {
            // Retrieve all insurance types for listing
            $insurances = $insuranceManager->getAllInsuranceTypes();

            // Set data for rendering the editor view (list of insurance types)
            $this->data['insurances'] = $insurances;

            // Set the view to render the editor (list) view
            $this->view = 'editor';

        }
    }
}