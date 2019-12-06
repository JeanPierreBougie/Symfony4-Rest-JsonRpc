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
use App\DTO\IndustryDTO;

class JobDTO implements IDTO
{
    private $id;
    private $titleFr;
    private $titleEn;
    private $descriptionFr;
    private $descriptionEn;
    private $hiringOrganization;
    private $datePosted;
    private $salary;
    private $industry;
    private $benefits;
    private $displayEndedAt;
    private $schedule;
    private $startDate;
    private $confidential;
    private $externalApplicationUrlFr;
    private $externalApplicationUrlEn;
    private $deleted;
    private $language;
    private $mobility;
    private $externalEmail;
    private $referenceNumber;
    private $broadcastStatus;
    private $confidentialNameFr;
    private $confidentialNameEn;
    private $displayed;
    private $scheduleDate;
    private $validThrough;
    private $jobDraftId;
    private $deletedAtJobDraft;
    private $displayedAt;
    private $highVisibility;
    private $employerLogoFr;
    private $employerLogoEn;

    private $location = array();
    private $arrDimensions = array();
    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct($id, $strJobTitleFr, $strJobTitleEn, $strJobDescriptionFr,
        $strJobDescriptionEn, $strDatePosted = null, $jsonSalary = null,
        $strIndustryId, $strInduShortNameFr = null, $strInduNameFr, $strInduSlugFr, $strInduDescriptionFr, $strInduDisplayOrderFr,
        $strInduShortNameEn = null, $strInduNameEn, $strInduSlugEn, $strInduDescriptionEn, $strInduDisplayOrderEn,
        $strLocationPC = null, $strDefaultCityName, $strLocationProvince = null, $strBenefits = null, $dateDisplayEndedAt = null, $floatSchedule = null, $scheduleType,
        $strScheduleShift = null, $strStartDate = null, $strConfidential = null, $strExternalApplicationUrlFr = null,
        $strExternalApplicationUrlEn = null, $strDeleted = null, $strLanguage = null, $strMobility = null, $strExternalEmail = null,
        $strReferenceNumber = null, $strBroadcastStatus = null, $strConfidentialNameFr = null, $strConfidentialNameEn = null, $strDisplayed = null, $strScheduleDate = null,
        $contactId, $contactFirstName, $contactLastName, $employerId, $employerNameFr, $employerNameEn, $employerDescriptionFr, $employerDescriptionEn, $employerProfilId, $employerCorpoId, $intCityId,$intRegionId, $jobDraftId=null, $deletedAtJobDraft=null, $displayedAt=null
    ) {

        //change deleted field A for false and I for true;
        if ($strDeleted == "A") { $this->deleted = false; }
        elseif ($strDeleted == "I") { $this->deleted = true; }

        //Construct industry array
        $this->industry = array();
        $this->industry[] = new IndustryDTO($strIndustryId,$strInduNameEn,$strInduNameFr,$strInduShortNameFr,$strInduShortNameEn,$strInduSlugEn,$strInduSlugFr,$strInduDescriptionEn,$strInduDescriptionFr,null,null,null);


        $this->cityId = $intCityId;
        $this->postalCode = $strLocationPC;
        $this->province = $strLocationProvince;
        $this->cityName = $strDefaultCityName;



        //Construct schedule array
        $this->schedule = [
            "hours" => $floatSchedule,
            "shift" => $strScheduleShift,
            "type" => $scheduleType,
        ];

        $this->id = $id;
        $this->titleFr = $strJobTitleFr;
        $this->titleEn = $strJobTitleEn;
        $this->descriptionFr = $this->trimToNull($strJobDescriptionFr);
        $this->descriptionEn = $this->trimToNull($strJobDescriptionEn);

        $this->datePosted = $strDatePosted->format('Y-m-d H:i:s');
        $this->salary = $jsonSalary;
        $this->benefits = $strBenefits;
        $this->displayEndedAt = $dateDisplayEndedAt;
        $this->startDate = $strStartDate;
        $this->confidential = $this->transformStringToBool($strConfidential);
        $this->externalApplicationUrlFr = $strExternalApplicationUrlFr;
        $this->externalApplicationUrlEn = $strExternalApplicationUrlEn;
        $this->language = $this->iso2($strLanguage);
        $this->mobility = $strMobility;
        $this->externalEmail = $strExternalEmail;
        $this->referenceNumber = $strReferenceNumber;
        $this->broadcastStatus = $strBroadcastStatus;
        $this->confidentialNameFr = $strConfidentialNameFr;
        $this->confidentialNameEn = $strConfidentialNameEn;
        $this->displayed = $this->transformStringToBool($strDisplayed);
        $this->scheduleDate = $strScheduleDate;
        $this->jobDraftId = $jobDraftId;
        $this->deletedAtJobDraft = $deletedAtJobDraft;
        $this->regionId = $intRegionId;
        $this->displayedAt = $displayedAt;

        $this->hiringOrganization = [
            // add employer info
            'id' => $employerId,
            'profileId' => $employerProfilId,
            'corpoId' => $employerCorpoId,
            'contact' => [
                'id' => $contactId,
                'firstName' => $contactFirstName,
                'lastName' => $contactLastName,
            ],
            'locale' => [
                'fr' => [
                    'name' => $employerNameFr,
                    'description' => $employerDescriptionFr,
                ],
                'en' => [
                    'name' => $employerNameEn,
                    'description' => $employerDescriptionEn
                ],
            ]
        ];
    }

    public function getPostalCode(){
        return $this->postalCode;
    }

    public function getRegionName(){
        return $this->province;
    }
    public function getRegionId(){
        return $this->regionId;
    }
    public function getCountryName(){
        return '';
    }

    public function getCityId(){
        return $this->cityId;
    }

    public function getCityName(){
        return $this->cityName;
    }

    /**
     * attache dimensions to the DTO object
     * @param string $dimensionName
     * @param \App\DTO\IDTO $dimensionInfo
     */
    public function addDimension(string $dimensionName,CollectionDTO $dimensionInfo){
        $this->arrDimensions[$dimensionName] = $dimensionInfo;
    }

    /**
     * used for json_encode
     * @return array
     */
    public function jsonSerialize() {
        $arrReturn = array();
        $arrReturn["id"]=$this->id;

        // "datePosted": "2018-02-28 10:46:43",
        //$arrReturn["datePosted"] = ;
        $objDatePosted = new \DateTime();
        $arrReturn["datePosted"] = $objDatePosted->setTimestamp(strtotime($this->datePosted))->format(\DateTime::ATOM);
        $arrReturn["salary"]=$this->salary;
        $arrReturn["industry"]=$this->industry;
        $arrReturn["benefits"]=$this->benefits;

        $arrReturn["location"]=$this->location;
        $arrReturn["schedule"]=$this->schedule;
        $arrReturn["startDate"]=$this->startDate;
        $arrReturn["confidential"]=$this->confidential;

        $arrReturn["deleted"] = $this->deleted;
        $arrReturn["language"] = $this->language;
        $arrReturn["mobility"] = $this->mobility;
        $arrReturn["applyExternalEmail"] = $this->externalEmail;
        $arrReturn["referenceNumber"] = $this->referenceNumber;
        $arrReturn["broadcastStatus"] = $this->broadcastStatus;
        /*$arrReturn["confidentialNameFr"] = $this->confidentialNameFr;
        $arrReturn["confidentialNameEn"] = $this->confidentialNameEn;*/
        $arrReturn["displayed"] = $this->displayed;
        // "validThrough": "Mon, 16 Apr 2018 10:53:13 GMT",
        $arrReturn["validThrough"] = $this->datetimeToAtom($this->validThrough);

        $arrReturn["locale"] = array();
        $arrReturn["locale"]['fr']['title'] =$this->titleFr;
        $arrReturn["locale"]['fr']['description'] =$this->descriptionFr;
        $arrReturn["locale"]['fr']['applyExternalUrl'] =$this->externalApplicationUrlFr;
        $arrReturn["locale"]['fr']['confidentialName'] =$this->confidentialNameFr;

        $arrReturn["locale"]['en']['title'] =$this->titleEn;
        $arrReturn["locale"]['en']['description'] =$this->descriptionEn;
        $arrReturn["locale"]['en']['applyExternalUrl'] =$this->externalApplicationUrlEn;
        $arrReturn["locale"]['en']['confidentialName'] =$this->confidentialNameEn;

        $arrReturn["hiringOrganization"] = $this->hiringOrganization;
        $arrReturn["hiringOrganization"]["locale"]["fr"]["logo"] = $this->employerLogoFr;
        $arrReturn["hiringOrganization"]["locale"]["en"]["logo"] = $this->employerLogoEn;
        
        $arrReturn["highVisibility"] = $this->highVisibility;
        $arrReturn["displayedAt"] = $this->datetimeToAtom($this->displayedAt);

        if ($this->jobDraftId!=null)
        {
            ($this->deletedAtJobDraft) ? $arrReturn["isDraft"] = false : $arrReturn["isDraft"] = true;
        }
        else { $arrReturn["isDraft"] = false; }

        /* attached dimensions */
        if(!empty($this->arrDimensions)){
            $arrReturn["dimensions"] = $this->arrDimensions;
        }
        return $arrReturn;
    }

    public function setJobLocation(LocationDTO $location){
        $this->location = $location;
    }

    public function setHighVisibility(bool $hv) {
        $this->highVisibility = $hv;
    }

    public function transformStringToBool(string $val=null)
    {
        if ($val == "Y") { return true; }
        elseif ($val == "N") { return false; }
        else { return null; }
    }

    private function iso2(string $value)
    {
        if ($value == "F") { return "fr"; }
        if ($value == "A") { return "en"; }
        else { return $value; }
    }

    public function trimToNull(string $value=null)
    {
        /*Fonction qui s'assure qu'une description ne contient pas seulement des retours de ligne ou des espaces.
        * Si c'est le cas, on retourne null.*/
        $strTrim = trim($value);
        return (strlen($strTrim)>0) ? $value : null;
    }

    public function getDisplay()
    {
        return $this->displayed;
    }
    public function getDisplayEndedAt()
    {
        return $this->displayEndedAt;
    }
    public function getScheduleDate()
    {
        return $this->scheduleDate;
    }

    public function setValidThrough(\DateTime $value)
    {
        $this->validThrough = $value;
    }

    public function getHiringOrganization()
    {
        return $this->hiringOrganization;
    }

    public function setEmployerLogoFr(string $employerLogoFr)
    {
        $this->employerLogoFr = $employerLogoFr;
    }
    public function setEmployerLogoEn(string $employerLogoEn)
    {
        $this->employerLogoEn = $employerLogoEn;
    }

    private function datetimeToAtom(\DateTime $value = null)
    {
        if ($value != null) {
            return $value->format(\DateTime::ATOM);
        } else { return null; }
    }
}
