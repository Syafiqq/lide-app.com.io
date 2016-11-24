<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 11 November 2016, 11:22 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mcontent extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function add($value)
    {
        $query = 'INSERT INTO `add` VALUES (?)';
        $data = array($value);
        if ($this->db->query($query, $data))
        {
            return "Success";
        }
        else
        {
            return "Failed";
        }
    }
}