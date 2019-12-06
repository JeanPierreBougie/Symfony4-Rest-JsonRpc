<?php
/**
 * Created by PhpStorm.
 * User: wperron
 * Date: 5/8/2018
 * Time: 11:50
 */

namespace App\Entity\Type;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class LangIso2Type extends StringType
{
    const TYPE = 'langIso2'; // modify to match your type name

    const FR = 'fr';
    const EN = 'en';


    const VALUES = [
        self::FR,
        self::EN
    ];


    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        // return the SQL used to create your column type. To create a portable column type, use the $platform.
        return $platform->getVarcharTypeDeclarationSQL(['length' => 2]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        // This is executed when the value is read from the database. Make your conversions here, optionally using the $platform.
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        // This is executed when the value is written to the database. Make your conversions here, optionally using the $platform.

        if(!in_array($value, self::VALUES)) {
            throw new \InvalidArgumentException("Invalid Status [".$value."], possibles [".implode(',',self::VALUES)."]");
        }

        return $value;
    }

    public function getName()
    {
        return self::TYPE; // modify to match your constant name
    }

    public static function getAvailableValues(){
        return self::VALUES;
    }

}