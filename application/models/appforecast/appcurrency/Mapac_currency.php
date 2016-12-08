<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 25 November 2016, 10:17 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapac_currency extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function getAllData()
    {
        $query = 'SELECT `id`, `name`, `code` FROM  `oauth_001`.`currency`';
        $result = $this->db->query($query);
        return $result->result_array();
    }
}