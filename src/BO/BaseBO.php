<?php
/**
 * Created by PhpStorm.
 * User: jpbougie
 * Date: 2017-12-06
 * Time: 11:20
 */

namespace App\BO;

class BaseBO{
    protected $version ='v1';

    /**
     * used for versionning
     * @param string $version
     */
    public function setVersion(string $version){
        $this->version = $version;
    }

    /**
     * diplay the supported dimensions
     * @return Array
     */
    public function fetchSupportedDimensions():Array{
        return (array) self::DIMENSIONS;
    }
}