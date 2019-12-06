<?php
namespace App\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ConflictException extends BaseException
{
    public function __construct(array $arrDataInfo = array(), $previous = null)
    {
        parent::__construct(409,  json_encode($arrDataInfo), $previous);
    }

}