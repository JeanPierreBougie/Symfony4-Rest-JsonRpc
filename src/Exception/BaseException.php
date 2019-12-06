<?php
namespace App\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseException extends HttpException
{
    /**
     * Constructor.
     *
     * @param string    $message  The internal exception message
     * @param Exception $previous The previous exception
     * @param integer   $code     The internal exception code
     */
    public function __construct($code = 0, $message = '', $previous = null)
    {
        parent::__construct( $code, $message, $previous);
    }

    public function jsonSerialize() {
        $arrReturn = [
            "code" => $this->getCode(),
            "message" => $this->getMessage(),
            "businessRefCode" => $this->getCode()
        ];

        return $arrReturn;
    }
}