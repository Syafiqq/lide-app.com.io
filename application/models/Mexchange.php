<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 02 December 2016, 4:58 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mexchange extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function storeOrUpdate($from, $to, $date, $value)
    {
        $query = 'call store_or_update_exchange(?, ?, ?, ?)';
        $this->db->query($query, array($from, $to, $date, $value));
    }

    public function loadValueWithSpecificDate($from, $to, $start, $total)
    {
        $query = 'SELECT `table1`.`value` FROM (SELECT `date`, `value` FROM `exchange` WHERE `date` > ? AND `base` = ? AND `to` = ? AND `value` IS NOT NULL ORDER BY `date` ASC LIMIT ?)`table1` ORDER BY `table1`.`date` DESC';
        $result = $this->db->query($query, array($start, (int)$from, (int)$to, $total));
        $data = $result->result_array();
        if ($result != null)
        {
            $this->sanitize($data);
        }
        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function sanitize(&$data)
    {
        foreach ($data as $key => $value)
        {
            $data[$key] = $value['value'];
        }
        return $data;
    }

    public function loadAllForTesting($from, $to)
    {
        $query = 'SELECT `date`, `value` FROM `exchange` WHERE `base` = ? AND `to` = ? AND `value` IS NOT NULL ORDER BY `date` DESC';
        $result = $this->db->query($query, array((int)$from, (int)$to));
        $data = $result->result_array();
        return count($data) > 0 ? $data : array();
    }

    /**
     * @param array $data
     * @param $feature
     * @return array
     */
    public function formatData($data, $feature)
    {
        $currency = array();
        for ($i = -1, $is = count($data) - $feature, $js = $feature; ++$i < $is;)
        {
            $feature = array();
            for ($j = -1; ++$j < $js;)
            {
                array_push($feature, $data[$i + ($js - $j)]);
            }
            array_push($currency, array('data' => $feature, 'class' => array('expected' => $data[$i])));
        }
        return $currency;
    }

    /**
     * @param array $data
     * @param $feature
     * @return array
     */
    public function formatDataTesting($data, $feature)
    {
        $currency = array();
        for ($i = -1, $is = count($data) - $feature + 1, $js = $feature; ++$i < $is;)
        {
            $feature = array();
            for ($j = 0; ++$j <= $js;)
            {
                array_push($feature, $data[$i + ($js - $j)]['value']);
            }
            array_push($currency, array('data' => $feature, 'date' => $data[$i]['date']));
        }
        return $currency;
    }

    public function assignPredict($from, $to, $data)
    {
        $query = 'call store_or_update_predict_exchange(?, ?, ?, ?)';
        $this->db->trans_start();
        foreach ($data as $rv)
        {
            $this->db->query($query, array((int)$from, (int)$to, $rv['date'], (double)$rv['class']['actual']));
        }
        $this->db->trans_complete();
    }

    public function removeExchangeValueForward($date, $from, $to)
    {
        $query = 'DELETE FROM `exchange` WHERE `base` = ? AND `to` = ? AND `date` > ?';
        $this->db->query($query, array((int)$from, (int)$to, $date));
    }

    /**
     * @param $from
     * @param $to
     * @param $finish
     * @param $total
     * @return mixed
     */
    public function loadValueFromWithLimit($from, $to, $finish, $total)
    {
        $query = 'SELECT `table1`.`value` FROM (SELECT `date`, `value` FROM `exchange` WHERE `date` <= ? AND `base` = ? AND `to` = ? AND `value` IS NOT NULL ORDER BY `date` DESC LIMIT ?)`table1` ORDER BY `table1`.`date` ASC';
        $result = $this->db->query($query, array($finish, (int)$from, (int)$to, (int)$total));
        $data = $result->result_array();
        if ($result != null)
        {
            $this->sanitize($data);
        }
        return $data;
    }

    public function getExchangeFromWithLimit($from, $to, $start, $total)
    {
        $query = 'SELECT `date`, `value`, `predicted` FROM `exchange` WHERE `date` >= ? AND `base` = ? AND `to` = ? AND `predicted` IS NOT NULL ORDER BY `date` ASC LIMIT ?';
        $result = $this->db->query($query, array($start, (int)$from, (int)$to, (int)$total));
        return $result->result_array();
    }
}