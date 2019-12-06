<?php
/**
 * Created by PhpStorm.
 * User: jpbougie
 * Date: 2017-12-06
 * Time: 11:20
 */

namespace App\Controller;

use App\BO\JobBO;
use App\DTO\MaintaskDTO;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Response\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class JobController
  */
class JobController extends Controller
{
    private $logger = null;
    private $jobBO = null;

    public function __construct(JobBO $jobBO,LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->jobBO = $jobBO;
    }

    /**
     * @Route("/{version}job/{id}",requirements={"version"="(v1/){0,1}"},defaults={"version" = "v1"},methods={"GET"})
     */
    public function indexAction($id,$version,Request $request)
    {
        // Set BusinessProcessVersion
        $this->jobBO->setVersion(trim($version,"/"));
        $arrDimensionsList =(array)$request->get('dimensions',array());

        try{
            $dtoJobInfo = $this->jobBO->fetchJobInfo($id,$arrDimensionsList);
            $jsonResponse =new JsonResponse($dtoJobInfo,200);
        }catch(\Exception $e){
            $jsonResponse =new JsonResponse($e);
            $this->logger->error($e->getMessage());
        }
        return $jsonResponse->getResponse();
    }

    /**
     * @Route("/{version}job/",requirements={"version"="(v1/){0,1}"},defaults={"version" = "v1"},methods={"GET"})
     */
    public function listAction($version,Request $request)
    {
        // Set BusinessProcessVersion
        $this->jobBO->setVersion(trim($version,"/"));
        $arrSearchParams = $request->query->all();

        try{
            $dtoJobList = $this->jobBO->fetchJobList($arrSearchParams);
            $jsonResponse =new JsonResponse($dtoJobList,200);
        }catch(\Exception $e){
            $jsonResponse =new JsonResponse($e);
            $this->logger->error($e->getMessage());
        }
        return $jsonResponse->getResponse();
    }
}