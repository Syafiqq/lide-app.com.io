<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 02 December 2016, 6:17 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Melm_profile extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    /**
     * @param string $name
     * @param int $from
     * @param int $to
     * @param string $method
     * @return mixed
     */
    public function load($name, $from, $to, $method)
    {
        $query = 'SELECT `id`, `name`, `from`, `to`, `start`, `total`, `min`, `max`, `feature`, `bias`, `method`, `accuracy`, `updated`, `active` FROM `elm_profile` WHERE `name` = ? AND `from` = ? AND `to` = ? AND `method` = ? LIMIT 1';
        $result = $this->db->query($query, array($name, (int)$from, (int)$to, $method));
        return $result->result_array();
    }

    public function updateBound($id, $min, $max)
    {
        $query = 'UPDATE elm_profile SET `min` = ?, `max`= ? WHERE `id` = ?';
        $this->db->query($query, array((int)$min, (int)$max, (int)$id));
    }

    public function updateAccuracy($id, $accuracy)
    {
        $query = 'UPDATE elm_profile SET `accuracy` = ? WHERE `id` = ?';
        $this->db->query($query, array((double)$accuracy, (int)$id));
    }

    public function loadBound($id)
    {
        $query = 'SELECT `min`, `max` FROM `elm_profile` WHERE `id` = ?';
        $result = $this->db->query($query, array((int)$id));
        return $result->result_array();
    }

    public function disableProfile($from, $to)
    {
        $query = 'UPDATE elm_profile SET `active` = 0 WHERE `from` = ? AND `to` = ?';
        $this->db->query($query, array((int)$from, (int)$to));
    }

    public function markAsActive($id)
    {
        $query = 'UPDATE elm_profile SET `active` = 1 WHERE `id` = ?';
        $this->db->query($query, array((int)$id));
    }

    public function loadAllActiveProfile()
    {
        $query = 'SELECT `id`, `name`, `from`, `to`, `start`, `total`, `min`, `max`, `feature`, `bias`, `method`, `accuracy`, `updated`, `active` FROM `elm_profile` WHERE `active` = 1';
        $profiles = $this->db->query($query);
        return $profiles->result_array();
    }
}