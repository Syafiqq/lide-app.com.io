<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 02 December 2016, 3:42 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mcurrency extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function getAllData()
    {
        $query = 'SELECT `id`, `name`, `code` FROM  `currency`';
        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function getData($id)
    {
        $query = 'SELECT `id`, `name`, `code` FROM `currency` WHERE `id` = ?';
        $result = $this->db->query($query, array((int)$id));
        return $result->result_array();
    }

    public function getDataFromCode($code)
    {
        $query = 'SELECT `id`, `name`, `code` FROM `currency` WHERE `code` = ?';
        $result = $this->db->query($query, array($code));
        return $result->result_array();
    }
}