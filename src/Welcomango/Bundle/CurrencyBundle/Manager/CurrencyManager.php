<?php

namespace Welcomango\Bundle\CurrencyBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use GuzzleHttp\Client;

use Welcomango\Model\Currency;

class CurrencyManager{

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager   = $entityManager;
    }

    public function updateCurrencyRates(){
        $currencyRepo = $this->entityManager->getRepository('Model:Currency');
        $currencies = $currencyRepo->findAll();

        $list = array();
        foreach($currencies as $currency){
            $list[] = 'EUR'.$currency->getCode();
        }

        $url = 'http://query.yahooapis.com/v1/public/yql?';
        $vars = 'q='.urlencode('select * from yahoo.finance.xchange where pair in ("'.implode('","',$list).'")').'&env='.urlencode('store://datatables.org/alltableswithkeys');

        $client = new Client();
        $res = $client->request('GET', $url.$vars);

        $xml = simplexml_load_string($res->getBody());

        foreach($xml->results->rate as $rate){
            $attributes = $rate->Attributes();
            $id = (string)$attributes['id'];
            if(strlen($id)!=6){
                continue;
            }
            $code = substr($id,3);

            foreach($currencies as $currency){
                if($currency->getCode() == $code){
                    $currency->setRate($rate->Rate);
                }
            }

            $this->entityManager->persist($currency);
        }

        $this->entityManager->flush();

    }
}