<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getCountries()
    {
        $result = $this->db->query('
            SELECT *
            FROM countries
            ORDER BY name ASC
        ');

        return $result;

    }

    public function getCountryById($country_id)
    {
        $result = $this->db->query('
            SELECT *
            FROM countries
             WHERE id = '.$country_id.'
            ORDER BY name ASC
        ');

        return $result->row();

    }

    public function getStatesByCountryId($country_id)
    {
        $result = $this->db->query('
            SELECT *
            FROM states
            WHERE country_id = '.$country_id.'
            ORDER BY name ASC
        ');

        return $result;

    }

    public function getCitiesByStateId($state_id)
    {
        $result = $this->db->query('
            SELECT *
            FROM cities
             WHERE state_id = '.$state_id.'
            ORDER BY name ASC
        ');

        return $result;

    }

    public function getStateById($state_id)
    {
        $result = $this->db->query('
            SELECT *
            FROM states
            WHERE id = '.$state_id.'
            ORDER BY name ASC
        ');

        return $result->row();

    }
}
