<?php
/**
 * Created by PhpStorm.
 * User: jpbougie
 * Date: 2017-12-12
 * Time: 12:41
 */

namespace App\Exception;

class MaintaskNotFoundException extends NotFoundException
{
    public function __construct(array $criteria=array(), $previous = null)
    {
        parent::__construct( array('code'=>404,'exception'=>'MaintaskNotFoundException','message'=>'maintask.not.found','criteria'=>$criteria), $previous);
    }
}