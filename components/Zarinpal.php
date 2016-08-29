<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Url;

set_time_limit(0);

class Zarinpal extends Component
{
    private $MerchantID = '0693bdac-d8a5-11e5-9d33-000c295eb8fc';
    private $wsdl = 'https://www.zarinpal.com/pg/services/WebGate/wsdl';
    
    public function request($Amount,$orderID,$Description='') {
        // orderID dar callbackURL gharar migirad baraye update database baad az pardakht
        $CallbackURL = Url::toRoute(['/site/facture','orderID'=>$orderID], true);
        $Description = $Description.'orderId='.$orderID;
        $client = new \SoapClient($this->wsdl, ['encoding' => 'UTF-8']);
        $result = $client->PaymentRequest(
                [
                'MerchantID' => $this->MerchantID,
                'Amount' => $Amount,
                'Description' => $Description,
                'CallbackURL' => $CallbackURL,
                ]
            );
        if ($result->Status == 100) {
            $url = 'https://www.zarinpal.com/pg/StartPay/'.$result->Authority;
            Header('Location: '. $url);
            exit('<meta httpd-equiv="refresh" content="0;url='.$url.'"/>');        
        }
        $error = 'ERR: '.$result->Status;
        return $error;
    }
    public function verify($Amount,$au) {
        $Authority = $au['Authority'];
        if ($au['Status'] == 'OK') {

            $client = new \SoapClient($this->wsdl, ['encoding' => 'UTF-8']);

            $result = $client->PaymentVerification(
                [
                'MerchantID' => $this->MerchantID,
                'Authority' => $Authority,
                'Amount' => $Amount,
                ]
            );

            if ($result->Status == 100) {
                return ['status'=>1,'error'=>'Transation success. RefID:'.$result->RefID,'au'=>$result->RefID];
            } else {
                return ['status'=>2,'error'=>'Transation failed. Status:'.$result->Status,'au'=>''];
            }
        } else {
            return ['status'=>0,'error'=>'Transaction canceled by user','au'=>''];
        }        
    }
}
