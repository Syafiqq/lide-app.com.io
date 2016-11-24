<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 11 November 2016, 9:35 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mauth extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function login($email, $password)
    {
        $query = 'SELECT `id`, `username`, `email`, `password` FROM `user` WHERE `email` = ? AND `password` = ? LIMIT 1';
        $data = array($email, md5(md5($password)));
        $result = $this->db->query($query, $data);
        return $result->result_array();
    }
}