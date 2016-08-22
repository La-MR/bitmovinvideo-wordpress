<?php
    /**
     * Created by PhpStorm.
     * User: cwioro
     * Date: 22.06.15
     * Time: 13:57
     */

    namespace test;

    require_once __DIR__ . '/../vendor/autoload.php';

    use bitcodin\Bitcodin;
    use test\BitcodinApiTestBaseClass;

    class ConcreteApiResource extends \bitcodin\ApiResource
    {
        public static function postRequest($url, $body, $expectedCode)
        {
            return self::_postRequest($url, $body, $expectedCode);
        }

        public static function patchRequest($url, $body, $expectedCode)
        {
            return self::_patchRequest($url, $body, $expectedCode);
        }

        public static function deleteRequest($url, $expectedCode)
        {
            return self::_patchRequest($url, NULL, $expectedCode);
        }
    }


    class ApiResourceTest extends BitcodinApiTestBaseClass
    {
        public function testErrorPostRequest()
        {
            Bitcodin::setApiToken($this->getApiKey());
            //$this->setExpectedException('\bitcodin\exceptions\BitcodinResourceNotFoundException');
            ConcreteApiResource::postRequest('/lkajljow/', '', 400);
        }

        public function testErrorDeleteRequest()
        {
            Bitcodin::setApiToken($this->getApiKey());
            //$this->setExpectedException('\bitcodin\exceptions\BitcodinResourceNotFoundException');
            ConcreteApiResource::deleteRequest('/lkajljow/', 404);
        }

        public function testErrorPatchRequest()
        {
            Bitcodin::setApiToken($this->getApiKey());
            //$this->setExpectedException('\bitcodin\exceptions\BitcodinResourceNotFoundException');
            ConcreteApiResource::patchRequest('/lkajljow/', NULL, 404);
        }

    }
