<?php
/**
 * Created by PhpStorm.
 * User: jpbougie
 * Date: 2017-12-07
 * Time: 10:53
 */

namespace App\DTO;
use App\DTO\CollectionDTO;

interface IDTO extends \JsonSerializable
{
    public function addDimension(string $dimensionName,CollectionDTO $dimensionInfo);
}