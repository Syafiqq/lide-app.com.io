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
}