<?php
/**
 * Created by PhpStorm.
 * User: jpbougie
 * Date: 2017-12-12
 * Time: 13:48
 */

namespace App\Response;

use App\DTO\CollectionDTO;
use App\DTO\IDTO;
use Symfony\Component\HttpFoundation\Response;

class JsonResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{

    protected $_response;
    protected $_content;
    protected $_contentStatus = 0;
    private $_headers;


    public function __construct($content = '', $status = 200, $headers = array())
    {
        $headers['Content-Type'] = 'application/json';
        $this->_content = $content;
        $this->_response = new Response('', $status, $headers);
    }


    /**
     * set the content
     * @param mixe $content
     * @param int $code
     */
    public function setContent($content, $code = 0)
    {
        $this->_content = $content;
        $this->_contentStatus = $code;
    }

    /**
     * set the http status code
     * @param int $code
     * @param string $text
     */
   # public function setStatusCode(int $code, $text = null)
#    {
#        $this->_response->setStatusCode($code, $text);
#    }

    /**
     * return the http response
     */
    public function getResponse()
    {
        $this->_response->setContent($this->_getContent($this->_content, $this->_contentStatus));
        return $this->_response;
    }

    /**
     * get the content that will be pushed into the http response
     * @param mixed $content
     * @param int $intStatus
     * @return string
     */
    protected function _getContent($content, $intStatus = "")
    {

        $stdReturn = new \StdClass();
        $stdReturn->status = new \stdClass();
        $stdReturn->status->code = (String)$intStatus;

        if (is_object($content)) {
            if ($content instanceOf \Exception) {

                $stdReturn->data = (object)array();
                $stdReturn->status->errorMessage = $content->getMessage();
                $error = array();
                $error['error']['message'] = $stdReturn->status->errorMessage;
                $error['error']['code'] = $content->getCode();

                if ($content instanceOf ValidatorException) {
                    $arrMessage = array();
                    foreach ($content->getMessages() as $constraintViolation) {
                        $arrMessage[$constraintViolation->getPropertyPath()] = $constraintViolation->getMessage();
                    }

                    $stdReturn->data = $arrMessage;
                    $arrInvalidValues = $content->getInvalidValues();
                    if (!empty($arrInvalidValues)) {
                        $error['error']['invalidValues'] = array();
                        foreach ($arrInvalidValues as $strInvalidValues) {
                            if (!empty($strInvalidValues))
                                $error['error']['invalidValues'][] = $strInvalidValues;
                        }
                    }
                }
                $stdReturn->errors[] = $error;
            }elseif($content instanceof IDTO || $content instanceof CollectionDTO){
                $stdReturn->data = $content;
            }
        } else {
            $stdReturn->data = $content;
        }

        return json_encode($stdReturn);
    }


    public function getHeaders()
    {
        return $this->_response->headers;
    }
}