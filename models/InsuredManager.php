<?php

/**
 * Class InsuredManager
 *
 * A class handling operations related to insured persons in the database.
 */
class InsuredManager
{
    /**
     * Retrieves detailed information about an insured person based on their URL.
     *
     * @param string $url The URL identifier of the insured person.
     * @return array|null An associative array containing insured person details if found; otherwise null.
     */
    public function getInsuredPerson(string $url): array
    {
        return Db::queryOne('
            SELECT 
                insured.insured_id, 
                insured.name, 
                insured.surname, 
                insured.identification_number, 
                insured.adress, 
                insured.email, 
                insured.phone_number, 
                insured.url,
                insurance.type_of_insurance,
                insurance.description,
                insurance.insurance_url
            FROM 
                insured
            JOIN 
                insurance ON insured.insurance_fk = insurance.insurance_id
            WHERE 
                insured.url = ?
            ', array($url));
    }

    /**
     * Retrieves all insured persons from the database.
     *
     * @return array An array of associative arrays representing insured persons.
     */
    public function getAllInsured(): array {
        return Db::queryAll('
        SELECT `insured_id`, `name`, `surname`, `identification_number`, `adress`, `url`
        FROM `insured`
        ORDER BY `insured_id` DESC 
        ');
    }

    /**
     * Saves or updates an insured person's information in the database.
     *
     * @param int|bool $id         The ID of the insured person to update, or false to insert a new record.
     * @param array $insuredData   An associative array containing the insured person's data.
     * @return void
     */
    public function saveInsuredPerson(int|bool $id, array $insuredData): void {
        if (!$id)
            Db::insert('insured', $insuredData);
        else
            Db::update('insured', $insuredData, 'WHERE insured_id = ?', array($id));
    }

    /**
     * Deletes an insured person from the database based on their URL.
     *
     * @param string $url The URL identifier of the insured person to delete.
     * @return void
     */
    public function deleteInsuredPerson(string $url): void {
        Db::query('
        DELETE FROM insured
        WHERE url = ?
        ', array($url));
    }
}