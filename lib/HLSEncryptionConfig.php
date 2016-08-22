<?php
/**
 * Created by PhpStorm.
 * User: doweinberger
 * Date: 04.08.15
 * Time: 14:43
 */

namespace bitcodin;

/**
 * Class HLSEncryptionConfig
 * @package bitcodin
 */
class HLSEncryptionConfig
{
    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $iv = null;

    /**
     * @var string
     */
    public $uri = null;

    /**
     * @return string
     */
    public function getRequestBody()
    {
        $array = [];
        $array['method'] = $this->method;
        $array['key'] = $this->key;

        if (!is_null($this->iv) && $this->iv !== '')
            $array['iv'] = $this->iv;

        if (!is_null($this->uri) && $this->uri !== '')
            $array['uri'] = $this->uri;

        return json_encode($array);
    }

}