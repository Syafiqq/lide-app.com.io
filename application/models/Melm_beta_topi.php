<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 02 December 2016, 9:21 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Melm_beta_topi extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    /**
     * @param int $profileID
     * @param array $beta_topi
     */
    public function add($profileID, $beta_topi)
    {
        $this->delete($profileID);
        $query = 'INSERT INTO `elm_beta_topi`(`profile`, `row`, `column`, `value`) VALUES ';
        $rm = count($beta_topi) - 1;
        foreach ($beta_topi as $rk => $rv)
        {
            $cm = count($rv) - 1;
            foreach ($rv as $ck => $cv)
            {
                if (($rk == 0) && ($ck == 0))
                {
                    $query .= "(${profileID}, ${rk}, ${ck}, ${cv})";
                }
                else if (($rk == $rm) && ($ck == $cm))
                {
                    $query .= ",(${profileID}, ${rk}, ${ck}, ${cv});";
                }
                else
                {
                    $query .= ",(${profileID}, ${rk}, ${ck}, ${cv})";
                }
            }
        }
        $this->db->query($query);
    }

    /**
     * @param int $profileID
     */
    public function delete($profileID)
    {
        $query = 'DELETE FROM `elm_beta_topi` WHERE `profile` = ?';
        $this->db->query($query, $profileID);
    }

    public function load($profileID)
    {
        $query = 'SELECT `column`, `value` FROM `elm_beta_topi` WHERE `profile` = ? ORDER BY `row`, `column` ASC';
        $result = $this->db->query($query, array((int)$profileID));
        return $this->sanitize($result->result_array());
    }

    /**
     * @param array $data
     * @return array
     */
    private function sanitize($data)
    {
        if (count($data) > 0)
        {
            $row = array();
            $column = array();
            $ic = 0;
            foreach ($data as $rv)
            {
                if ($rv['column'] == $ic)
                {
                    array_push($column, (double)$rv['value']);
                    ++$ic;
                }
                else
                {
                    array_push($row, $column);
                    $ic = 1;
                    unset($column);
                    $column = array();
                    array_push($column, (double)$rv['value']);
                }
            }
            array_push($row, $column);
            return $row;
        }
        else
        {
            return array();
        }
    }
}