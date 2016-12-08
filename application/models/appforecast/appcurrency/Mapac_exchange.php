<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 25 November 2016, 11:08 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapac_exchange extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function getTotalData($from, $to)
    {
        $query = "SELECT count(`exchange`.`base`) AS 'count' FROM  `oauth_001`.`exchange` LEFT OUTER JOIN `oauth_001`.`currency` ON `exchange`.`base` = `currency`.`id` AND  `exchange`.`to` = `currency`.`id` WHERE `exchange`.`base` = ? AND `exchange`.`to` = ?  LIMIT 1";
        $result = $this->db->query($query, array($from, $to));
        return $result->result_array()[0]['count'];
    }

    /**
     * @param int $from
     * @param int $to
     * @param int $total
     * @param boolean $isFlipped
     * @return mixed
     */
    public function getData($from, $to, $total, $isFlipped)
    {
        $query = "SELECT (" . ($isFlipped ? '1.0 / ' : '') . "`exchange`.`value`) as 'value' FROM  `oauth_001`.`exchange` LEFT OUTER JOIN `oauth_001`.`currency` ON `exchange`.`base` = `currency`.`id` AND  `exchange`.`to` = `currency`.`id` WHERE `exchange`.`base` = ? AND `exchange`.`to` = ?  ORDER BY `exchange`.`date` DESC LIMIT ?";
        $result = $this->db->query($query, array((int)$from, (int)$to, (int)$total));
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
}