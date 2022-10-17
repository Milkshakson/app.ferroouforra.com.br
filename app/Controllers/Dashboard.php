<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;
use CodeIgniter\I18n\Time;

class Dashboard extends BaseController
{
    public function yearly($year = null)
    {
        try {
            $this->dados['colorsRand'] = $hex = array_merge(range(0, 9), range('A', 'F'));
            $this->dados['colorsRand'] = sprintf('#06x', random_int(0, 0xFFFFFF));
            $year = $year ?? $year = date('Y');
            $this->dados['year'] = $year;
            $pokerSessionProvider = new PokerSessionProvider();
            $yearlySumary = $pokerSessionProvider->getResumoAnual($year);
            $barChartBuyIn = ['cores' => [], 'labels' => [], 'dataValues' => []];
            $barChartCotas = ['cores' => [], 'labels' => [], 'dataValues' => []];
            $barChartLucro = ['cores' => [], 'labels' => [], 'dataValues' => []];
            if ($yearlySumary['statusCode'] == 202) {
                $yearlySumary = $yearlySumary['content'];
                $this->dados['yearlySumary'] = $yearlySumary;
                foreach ($yearlySumary as $mes) {
                    /*Lucro*/
                    $barChartLucro['cores'][] =  $mes['profit'] >= 0 ? 'rgba(54, 162, 235, 0.2)' : 'rgba(255, 99, 132, 0.2)';
                    $barChartLucro['labels'][] = $mes['mesBuyin'];
                    $barChartLucro['dataValues'][] = $mes['profit'];
                    /*BuyIns */
                    $barChartBuyIn['cores'][] = 'rgba(54, 162, 235, 0.2)';
                    $barChartBuyIn['labels'][] = $mes['mesBuyin'];
                    $barChartBuyIn['dataValues'][] = $mes['totalBuyIn'];
                    /*cotas*/
                    $barChartCotas['cores'][] = 'rgba(54, 162, 235, 0.2)';
                    $barChartCotas['labels'][] = $mes['mesBuyin'];
                    $barChartCotas['dataValues'][] = $mes['stakingReturn'];
                }
            }

            $this->dados['barChartBuyIn'] = json_encode($barChartBuyIn, 1);
            $this->dados['barChartCotas'] = json_encode($barChartCotas, 1);
            $this->dados['barChartLucro'] = json_encode($barChartLucro, 1);
        } catch (APPException $e) {
            $this->dados['erros'] = $e->getHandledMessage($e->getMessage());
        }
        $this->view->display('Dashboard/yearly', $this->dados);
    }

    public function monthly($month = null, $year = null)
    {

        try {
            $this->dados['colorsRand'] = $hex = array_merge(range(0, 9), range('A', 'F'));
            $this->dados['colorsRand'] = sprintf('#06x', random_int(0, 0xFFFFFF));
            $year = $year ?? $year = date('Y');
            $month = $month ?? $month = date('m');
            $this->dados['year'] = $year;
            $this->dados['month'] = $month;
            $this->dados['CIMesResumo'] = Time::createFromFormat('m/Y', "$month/$year");
            $pokerSessionProvider = new PokerSessionProvider();
            $monthlySumary = $pokerSessionProvider->getResumoMensal(intval($month), intval($year));
            if ($monthlySumary['statusCode'] == 202) {
                $monthlySumary = $monthlySumary['content'];
                $this->dados['monthlySumary'] = $monthlySumary;
            }
        } catch (APPException $e) {
            $this->dados['erros'] = $e->getHandledMessage($e->getMessage());
        }
        $this->view->display('Dashboard/montly', $this->dados);
    }
}