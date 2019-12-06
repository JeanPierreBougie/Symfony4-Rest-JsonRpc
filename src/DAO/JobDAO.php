<?php
/**
 * Created by PhpStorm.
 * User: jpbougie
 * Date: 2017-12-06
 * Time: 16:03
 */

namespace App\DAO;
use App\DTO\ContactDTO;
use App\DTO\JobDTO;
use App\DTO\LocationDTO;
use App\Entity\Contact;
use App\Entity\Job;
use App\DTO\CollectionDTO;
use App\Entity\JobAbilities;
use App\Entity\JobJobType;
use App\Entity\JobMaintask;
use App\Entity\JobRequirements;
use App\Exception\CityNotFoundException;
use App\Exception\JobNotFoundException;
use App\Exception\PostalCodeNotFoundException;
use App\Helper\JobHelper;
use App\Helper\LogoHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class JobDAO
{
    private $em = null;
    private $mediator = null;

    public function __construct(EntityManagerInterface $em,MediatorDAO $mediatorDAO, LogoHelper $logoHelper){
        $this->em = $em;
        $this->mediator = $mediatorDAO;
        $this->logoHelper = $logoHelper;
    }

    /**
     * fetch the basic information about a job
     * @param $id
     * @return JobDTO
     */
    public function fetchJobInfo($id):JobDTO{
        try {
            $jobDTO = $this->em->getRepository(Job::class)->fetchJobInfo($id);

            $this->fetchEmployerLogo($jobDTO);
            return $this->fetchJobLocationInfo($jobDTO);
        }catch(NoResultException $e){
            throw new JobNotFoundException(['id'=>$id]);
        }
    }

    /**
     * fetch a list of job information
     * @param array $arrSearchCriteria
     * @return CollectionDTO
     */
    public function fetchJobList(array $arrSearchCriteria=array()):CollectionDTO{

        $searchCriteria = JobHelper::getSearchCriteriaFromParameters($arrSearchCriteria);
        $jobCollectionDTO = $this->em->getRepository(Job::class)->fetchJobList($searchCriteria);

        if(count($jobCollectionDTO->getCollectionList())) {
            foreach ($jobCollectionDTO->getCollectionList() as $key => $jobDTO) {
                $this->fetchEmployerLogo($jobDTO);
                $jobArray[$key] = $this->fetchJobLocationInfo($jobDTO);
            }

            $jobCollectionDTO = new CollectionDTO($jobArray);
        }
        return $jobCollectionDTO;
    }


    /**
     * fetch a list of maintask associated with job
     * @return CollectionDTO
     */
    public function fetchJobMaintaskList(string $jobId):CollectionDTO{

        $searchCriteria = JobHelper::getSearchMaintaskCriteriaFromParameters(array('jobId'=>$jobId,'limit'=>20));

        return $this->em->getRepository(JobMaintask::class)->fetchJobMaintaskList($searchCriteria);
    }
}