<?php

namespace App\Exception;

use Symfony\Component\Validator\Exception\ValidatorException as Sf2ValidatorException;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidatorException extends BaseException
{
    private $statusCode = 400;
    private $_constraintViolationList = null;
    
    /**
     * Constructor.
     *
     * @param string|array    $message  The internal exception message
     * @param Exception $previous The previous exception
     * @param integer   $code     The internal exception code
     */
    public function __construct(ConstraintViolationList $constraintViolationList, $message = null, \Exception $previous = null, $code = 0)
    {
        $this->_constraintViolationList = $constraintViolationList;
        $messages='';
        if(empty($messages)){
            $arrMessagesList = $this->_constraintViolationList->getIterator();
            $arrMessage = array();
            $arrValidMessages = array();
            foreach($arrMessagesList as $strKey=>$arrMessageInfo){
                if(!isset($arrMessage[$arrMessageInfo->getMessage()])) {
                    $arrValidMessages[]=$arrMessageInfo->getMessage();
                }
                $arrMessage[$arrMessageInfo->getMessage()]=1;
            }

            $messages = implode("\r\n",$arrValidMessages);
        }
        parent::__construct($this->statusCode,  json_encode(
            array('code'=>$this->statusCode,'exception'=>'ValidatorException','message'=>$messages,'messages'=>$arrValidMessages)

        ), $previous);

    }
}