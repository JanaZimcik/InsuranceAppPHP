<?php

/**
 * Class InsuranceManager
 *
 * A class handling operations related to insurance data in the database.
 */
class InsuranceManager
{
    /**
     * Retrieves information about a specific insurance based on its URL.
     *
     * @param string $insurance_url The URL identifier of the insurance.
     * @return array|null           An associative array containing insurance details if found; otherwise null.
     */
    public function getInsurance(string $insurance_url): array
    {
        return Db::queryOne('
            SELECT 
                insurance.insurance_id, 
                insurance.type_of_insurance, 
                insurance.description, 
                insurance.keywords,
                insurance.insurance_url
            FROM 
                `insurance`
            WHERE 
                insurance_url = ?
            ', array($insurance_url));
    }

    /**
     * Retrieves all insurance types from the database.
     *
     * @return array An array of associative arrays representing insurance types.
     */
    public function getAllInsuranceTypes(): array
    {
        return Db::queryAll('
            SELECT `insurance_id`, `type_of_insurance`
            FROM `insurance`
        ');
    }
}