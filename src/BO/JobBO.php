<?php
/**
 * Created by PhpStorm.
 * User: jpbougie
 * Date: 2017-12-06
 * Time: 11:20
 */

namespace App\BO;

use App\DAO\JobDAO;
use App\DTO\CollectionDTO;
use App\DTO\IDTO;
use App\DTO\JobDTO;
use Psr\Log\LoggerInterface;
use \DateTime;


class JobBO extends BaseBO implements IBO
{
    private $jobDAO = null;
    private $logger = null;

    const DIMENSIONS_MAINTASK = 'maintask';
    const DIMENSIONS = [self::DIMENSIONS_MAINTASK];

    public function __construct(JobDAO $jobDAO, LoggerInterface $logger)
    {
        $this->jobDAO = $jobDAO;
        $this->logger = $logger;
    }

    /**
     * Get a single job info to be displayed
     * @param $id
     * @param array $dimensionList
     * @return IDTO
     */
    public function fetchJobInfo($id, Array $dimensionList = array()): IDTO
    {
        $jobDto = $this->jobDAO->fetchJobInfo($id);
        $this->appendDimensions($jobDto,$dimensionList);

        $validThrough = $this->broadcastEndDate($jobDto->getScheduleDate(), $jobDto->getDisplayEndedAt(), $jobDto->getDisplay());

        if ($validThrough != null)
        {
            $validThrough_toDateTime = date_create($validThrough);
            $jobDto->setValidThrough($validThrough_toDateTime);
        }

        $jobDto->setHighVisibility($this->jobDAO->fetchHighVisibility($id));

        return $jobDto;
    }

    /**
     * get list of job by criteria to be displayed
     * @param array $arrSearchCriteria
     * @return CollectionDTO
     */
    public function fetchJobList(array $arrSearchCriteria = array(), Array $dimensionList = array()): CollectionDTO
    {
        $jobCollectionDTO = $this->jobDAO->fetchJobList($arrSearchCriteria);
        if(count($jobCollectionDTO->getCollectionList() )){
            foreach ($jobCollectionDTO->getCollectionList() as $key => $jobDTO) {
                $this->appendDimensions($jobDTO,$dimensionList);
                $jobArray[$key] = $jobDTO;
            }

            $jobCollectionDTO = new CollectionDTO($jobArray);
        }
        return $jobCollectionDTO;
    }

    /**
     * get dimensions for a specific job
     * @param int $id
     * @param array $dimensionList
     * @return array
     */
    public function fetchDimensions($jobId, Array $dimensionList = array()): array
    {
        $arrDimensionList = array();

        if (!empty($dimensionList)) {
            if (in_array(self::DIMENSIONS_MAINTASK, $dimensionList)) {
                $arrDimensionList[self::DIMENSIONS_MAINTASK] = $this->fetchJobMainTaskList($jobId);
            }
        }

        return $arrDimensionList;

    }

    public function fetchJobMainTaskList(string $id): CollectionDTO
    {

        return $this->jobDAO->fetchJobMaintaskList($id);
    }

    /**
     * Append dimension to job DTO
     * @param JobDTO $jobDto
     * @param array $dimensionList
     */
    private function appendDimensions(JobDTO $jobDto,array $dimensionList= array()){

        if (!empty($dimensionList)) {

            $arrReturnedDimensionList = $this->fetchDimensions($jobDto->getId(), $dimensionList);

            foreach ($arrReturnedDimensionList as $strDimensionName => $arrDimensionInfo) {
                $jobDto->addDimension($strDimensionName, $arrDimensionInfo);
            }

        }
    }
}