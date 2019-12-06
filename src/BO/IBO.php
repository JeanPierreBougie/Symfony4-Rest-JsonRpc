<?php
/**
 * Created by PhpStorm.
 * User: jpbougie
 * Date: 2017-12-07
 * Time: 11:02
 */

namespace App\BO;


interface IBO
{

    public function fetchDimensions($id,array $dimensionList=array()): array;

    public function fetchSupportedDimensions(): array;

    public function setVersion(string $version);
}