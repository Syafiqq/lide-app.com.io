<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 08 December 2016, 5:33 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
use Carbon\Carbon;

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function index()
    {

    }

    public function exchange()
    {/*
        if (isset($_SERVER['HTTP_X_REQUESTED_ORIGIN_TYPE']))
        {
            if (strcmp(strtolower($_SERVER['HTTP_X_REQUESTED_ORIGIN_TYPE']), 'lide_app_phone') === 0)
            {
                $values = json_decode(file_get_contents('php://input'), true);
                foreach ($values as $key => $value)
                {
                    $_GET[$key] = $value;
                }
                unset($values, $key, $value);
            }
        }*/

        $errorMessage = array();

        if (isset($_GET['from']) &&
            isset($_GET['to']) &&
            isset($_GET['date']) &&
            isset($_GET['total'])
        )
        {
            $this->load->model('mcurrency');
            $from = $this->mcurrency->getDataFromCode(strtolower($_GET['from']));
            if (count($from) > 0)
            {
                $from = $from[0];
                $to = $this->mcurrency->getDataFromCode(strtolower($_GET['to']));
                if (count($to) > 0)
                {
                    $this->load->library('session');
                    $to = $to[0];
                    $total = -1;
                    $date = "";
                    if (isset($_SESSION['user']['profile']))
                    {
                        if ($_GET['total'] > 7)
                        {
                            $total = 7;
                            $errorMessage['limit'] = 'maximum data can be obtained as premium user is 7';
                        }
                        else
                        {
                            $total = $_GET['total'];
                        }
                    }
                    else
                    {
                        if ($_GET['total'] > 3)
                        {
                            $total = 3;
                            $errorMessage['limit'] = 'maximum data can be obtained as free user is 3';
                        }
                        else
                        {
                            $total = $_GET['total'];
                        }
                    }

                    try
                    {
                        $date = Carbon::createFromFormat('Y-m-d', $_GET['date']);
                        if (Carbon::now()->diffInDays($date, false) > 0)
                        {
                            $date = Carbon::now()->toDateString();
                            $errorMessage['start'] = 'you can\'t start predict tomorrow or the day after';
                        }
                        else
                        {
                            $date = $date->toDateString();
                        }
                    }
                    catch (InvalidArgumentException $ignored)
                    {
                        $date = Carbon::now()->toDateString();
                        $errorMessage['start'] = "invalid date, mark date as today ${date}";
                    }

                    $this->load->model('mexchange');
                    $exchange = $this->mexchange->getExchangeFromWithLimit($from['id'], $to['id'], $date, $total);
                    $response = array(
                        'code' => '200',
                        'status' => 'accepted',
                        'data' => array(
                            'request' => array(
                                'from ' => $_GET['from'],
                                'to' => $_GET['to'],
                                'date' => $_GET['date'],
                                'total' => $_GET['total']
                            ),
                            'response' => array(
                                'from ' => $_GET['from'],
                                'to' => $_GET['to'],
                                'date' => $date,
                                'total' => $total,
                                'exchange' => $exchange,
                                'message' => $errorMessage
                            )
                        ));
                    echo json_encode($response);
                }
                else
                {
                    echo json_encode(array('code' => 406, 'message' => "Invalid Currency to parameter ${_GET['to']}"));
                }
            }
            else
            {
                echo json_encode(array('code' => 406, 'message' => "Invalid Currency to parameter ${_GET['from']}"));
            }
        }
        else
        {
            echo json_encode(array('code' => 407, 'message' => 'Insufficient data', 'data' => array('required' =>
                array('code' => 'from', 'desc' => 'Currency From {aud, cad, chf ...}'),
                array('code' => 'to', 'desc' => 'Currency To {ide, usd, eur ...}'),
                array('code' => 'date', 'desc' => 'Start date prediction <= ' . \Carbon\Carbon::now()->toDateString()),
                array('code' => 'to', 'total' => 'Total prediction {free = 3, premium = 7}'),
            )));
        }

    }

    public function simulate()
    {
        $this->load->library('session');
        $gate = false;
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_SESSION['user']))
            {
                $gate = true;
            }
        }

        if (!$gate)
        {
            if (isset($_GET['8e69d4fa7fe33ea3f33e112bcb2f57fb48e15aff09c505c220164f07654ef12c']))
            {
                if ($_GET['8e69d4fa7fe33ea3f33e112bcb2f57fb48e15aff09c505c220164f07654ef12c'] != 'b7be943a2f820d700428cebf63729769e6de6753e8e6dad18af2af92c3be55ac')
                {
                    echo json_encode(array('code' => 403, 'message' => 'Access Denied'));
                    return;
                }
            }
            else
            {
                echo json_encode(array('code' => 403, 'message' => 'Access Denied'));
                return;
            }
        }


        $this->load->model('melm_profile');
        $profiles = $this->melm_profile->loadAllActiveProfile();
        foreach ($profiles as $profile)
        {
            $this->doSimulate($profile);
        }
        echo json_encode(array('code' => 200, 'message' => 'Accepted'));
    }

    private function doSimulate($profile)
    {
        $this->load->model('mcurrency');
        $from = $this->mcurrency->getData($profile['from']);
        if (count($from) > 0)
        {
            $from = $from[0];
            $to = $this->mcurrency->getData($profile['to']);
            if (count($to) > 0)
            {
                $to = $to[0];
                $time = Carbon::now('Asia/Jakarta')->addDay(-1);
                $jsonp = false;
                do
                {
                    $jsonp = $this->getDataFixerIO($time->toDateString(), strtoupper($from['code']), strtoupper($to['code']));
                }
                while (!$jsonp);

                if ($jsonp)
                {
                    $jsonp = json_decode($jsonp, true);
                    $this->load->model('mexchange');
                    $this->mexchange->removeExchangeValueForward($time->toDateString(), $from['id'], $to['id']);
                    $this->mexchange->storeOrUpdate($from['id'], $to['id'], $time->toDateString(), $jsonp['rates'][strtoupper($to['code'])]);
                    $this->doPredict($profile, $time, 30);
                }
            }
        }
    }

    private function getDataFixerIO($now, $from, $to)
    {
        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, "http://api.fixer.io/${now}?base=${from}&symbols=${to}");
        // Execute
        $result = curl_exec($ch);
        // Closing
        curl_close($ch);

        return $result;
    }

    /**
     * @param array $profile
     * @param Carbon $now
     * @param int $days
     */
    private function doPredict($profile, $now, $days)
    {
        $this->load->model('melm_profile');
        $minMax = $this->melm_profile->loadBound($profile['id']);
        if (count($minMax) > 0)
        {
            $minMax = $minMax[0];
            $minMax['range'] = $minMax['max'] - $minMax['min'];

            $this->load->model('melm_weight');
            $weight = $this->melm_weight->load($profile['id']);
            if (count($weight) > 0)
            {
                $this->load->model('melm_bias');
                $bias = $this->melm_bias->load($profile['id']);
                if (count($bias) > 0)
                {
                    $this->load->model('melm_beta_topi');
                    $beta_topi = $this->melm_beta_topi->load($profile['id']);
                    if (count($bias) > 0)
                    {
                        $this->load->model('mexchange');
                        $raw = $this->mexchange->loadValueFromWithLimit($profile['from'], $profile['to'], $now->toDateString(), $profile['feature']);
                        $formatted = array(array(
                            'data' => $raw,
                            'date' => ($now = $now->addDay(1))->toDateString()
                        ));

                        $this->load->model('melm');
                        $this->melm->registerMetadata($profile['feature'], $weight, $bias, $minMax, $profile['bias'] == 1 ? true : false, $profile['method']);
                        $this->melm->assignOutputWeight($beta_topi);
                        for ($ct = -1, $cs_t = $days, $t_feature = $profile['feature']; ++$ct < $cs_t;)
                        {
                            $this->melm->test($formatted);
                            $this->mexchange->assignPredict(
                                $profile['from'],
                                $profile['to'],
                                $formatted);
                            //log_message('ERROR', var_export(array($formatted[0]), true));
                            foreach ($formatted as $tk => $tkv)
                            {
                                for ($cf = -1, $cs_f = $t_feature - 1; ++$cf < $cs_f;)
                                {
                                    $formatted[$tk]['data'][$cf] = $formatted[$tk]['data'][$cf + 1];
                                }
                                $formatted[$tk]['data'][$t_feature - 1] = $formatted[$tk]['class']['actual'];
                                $formatted[$tk]['date'] = ($now = $now->addDay(1))->toDateString();
                            }
                        }
                    }
                }
            }
        }
    }
}