<?php
/**
 * Created by PhpStorm.
 * User: jpbougie
 * Date: 2017-12-12
 * Time: 12:41
 */

namespace App\Exception;

class NotFoundException extends BaseException
{

    public function __construct(array $arrDataInfo = array(), $previous = null)
    {
        parent::__construct(404,  json_encode($arrDataInfo), $previous);
    }

}