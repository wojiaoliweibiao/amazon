<?php
namespace app\index\controller;

use think\Db;
use think\Loader;
use think\Controller;


class Index  extends Permission
{
    // North America:
    public $serviceUrl = "https://mws.amazonservices.com/Orders/2013-09-01";

    public $config = [];

    public function Index()
    {


       // $this->getorders()

     // var_dump($_SESSION);
     // var_dump($user);
        $arr=array();
        
       // $data=Db::name('order')->limit(10)->order('order_time desc')->select();
       // echo db::getlastsql();
       // dump($data);
        return $this->fetch('index');


    }


    public function givelevel(){


        $this->config = [
            'ServiceURL' => $this->serviceUrl,
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'ProxyUsername' => null,
            'ProxyPassword' => null,
            'MaxErrorRetry' => 3,
        ];
        $server=$this->serviceUrl;
        $config=$this->config;

        echo $server;
        var_dump($config);

        // var_dump($_GET);
        // 店铺别名
        // define('APPLICATION_NAMES',$_GET['amaShopName']);
        // 亚马逊账号
        // define('AWS_ACCESS_KEY_ID',$_GET['']);
        // define('AWS_SECRET_ACCESS_KEY',$_GET['awsAccessKeyId']);
        // define('AWS_ACCESS_KEY_ID',$_GET['secretKey']);
        // define('ssss', '1223');
    
        echo '正在授权';


        // $this->getorders();
    }
    public function order(){
        // echo 1;
      return  $this->fetch('order');
    }


    public function getorders(){
        define('AWS_ACCESS_KEY_ID', 'AKIAJ5U6OZJKLQSSDTEA');
        define('AWS_SECRET_ACCESS_KEY', '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');
        define('APPLICATION_NAME', 'miaoyanjun');
        define ('MERCHANT_ID', 'A2AUIKWDKUD2ZG');
        define ('MARKETPLACE_ID', 'ATVPDKIKX0DER');
        $this->config = [
            'ServiceURL' => $this->serviceUrl,
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'ProxyUsername' => null,
            'ProxyPassword' => null,
            'MaxErrorRetry' => 3,
        ];
        $requestData = [
            'MWSAuthToken' => 'amzn.mws.ccb17e01-312c-6496-0205-3d3a3ac65cc9',
            'CreatedAfter' => '2016-01-18T23:41:00Z',
            //'CreatedBefore' => '2018-04-27T16%3A00%3A00Z',
            //'LastUpdatedAfter' => array('FieldValue' => null, 'FieldType' => 'string'),
            //'LastUpdatedBefore' => array('FieldValue' => null, 'FieldType' => 'string'),
            //'OrderStatus' => array('FieldValue' => array(), 'FieldType' => array('string'), 'ListMemberName' => 'Status'),
            //'MarketplaceId' => 'ATVPDKIKX0DER HTTP/1.1',
            //'FulfillmentChannel' => array('FieldValue' => array(), 'FieldType' => array('string'), 'ListMemberName' => 'Channel'),
            //'PaymentMethod' => array('FieldValue' => array(), 'FieldType' => array('string'), 'ListMemberName' => 'Method'),
            //'BuyerEmail' => array('FieldValue' => null, 'FieldType' => 'string'),
            //'SellerOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
            //'MaxResultsPerPage' => array('FieldValue' => null, 'FieldType' => 'int'),
            //'TFMShipmentStatus' => array('FieldValue' => array(), 'FieldType' => array('string'), 'ListMemberName' => 'Status'),
        ];

        Loader::import('MarketplaceWebServiceOrders/Client', EXTEND_PATH);
        Loader::import('MarketplaceWebServiceOrders/Model/ListOrdersRequest', EXTEND_PATH);
        $request = new \MarketplaceWebServiceOrders_Model_ListOrdersRequest($requestData);
        $request->setSellerId(MERCHANT_ID);
        //print_r($request);die;
        $service = new \MarketplaceWebServiceOrders_Client(
            AWS_ACCESS_KEY_ID,
            AWS_SECRET_ACCESS_KEY,
            APPLICATION_NAME,
            APPLICATION_VERSION,
            $this->config);
        // dump($service);die;

        $result = $this->invokeListOrders($service, $request);
        // exit;
        // echo 11;
        // echo strlen('hello你好世界');

        dump($result);
        $data = $result->getOrders();
        dump($data);
        // dump(\MarketplaceWebServiceOrdersebServiceOrders_Model_ListOrdersResult::getOrders());
    }
    public function invokeListOrders(\MarketplaceWebServiceOrders_Interface $service, $request)
    {
        try {
            $response = $service->ListOrders($request);
            //dump($response);die;
            echo '<pre>';
            //echo("Service Response\n");
            //echo("=============================================================================\n");

            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            //echo $dom->saveXML();
            //echo("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
            // return $response->getResponseHeaderMetadata();
            return $response->getListOrdersResult();
        } catch (\MarketplaceWebServiceOrders_Exception $ex) {
            echo("Caught Exception: " . $ex->getMessage() . "\n");
            echo("Response Status Code: " . $ex->getStatusCode() . "\n");
            echo("Error Code: " . $ex->getErrorCode() . "\n");
            echo("Error Type: " . $ex->getErrorType() . "\n");
            echo("Request ID: " . $ex->getRequestId() . "\n");
            echo("XML: " . $ex->getXML() . "\n");
            echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
        }
    }
}
	