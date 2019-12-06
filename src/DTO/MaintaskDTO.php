<?php
/**
 * Created by PhpStorm.
 * User: jpbougie
 * Date: 2017-12-07
 * Time: 10:36
 */

namespace App\DTO;
use App\DTO\IDTO;
use App\DTO\CollectionDTO;

class MaintaskDTO implements IDTO
{
    private $id = null;
    private $parentId = null;
    private $industryId = null;
    private $titleEn = null;
    private $titleFr = null;
    private $slugEn = null;
    private $slugFr = null;


    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct($id,$parentId,$industryId,$slugEn,$titleEn,$slugFr,$titleFr)
    {
        $this->id = $id;
        $this->parentId = $parentId;
        $this->industryId = $industryId;
        $this->slugEn = $slugEn;
        $this->slugFr = $slugFr;
        $this->titleEn = $titleEn;
        $this->titleFr = $titleFr;

    }

    public function addDimension(string $dimensionName,CollectionDTO $dimensionInfo){}

    public function jsonSerialize() {
        $arrReturn = array();
        $arrReturn["id"]=$this->id;
        $arrReturn["parentId"]=$this->parentId;
        $arrReturn["industryId"]=$this->industryId;

        $arrReturn["locale"] = array();
        $arrReturn["locale"]['fr']['slug'] =$this->slugFr;
        $arrReturn["locale"]['fr']['title'] =$this->titleFr;
        $arrReturn["locale"]['en']['slug'] =$this->slugEn;
        $arrReturn["locale"]['en']['title'] =$this->titleEn;

        return $arrReturn;
    }
}