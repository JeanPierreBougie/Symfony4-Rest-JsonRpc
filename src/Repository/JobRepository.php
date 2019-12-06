<?php

namespace App\Repository;

use App\Entity\Job;
use App\Entity\JobType;
use App\Entity\Type\ActiveType;
use App\Entity\Type\YesNoType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\DTO\JobDTO;
use App\DTO\CollectionDTO;

class JobRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Job::class);
    }

    public function jobDtoQuery() {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select(
                'NEW App\DTO\JobDTO(job.id,job.titleFr,job.titleEn,transFr.description,transEn.description,
                    job.createdAt, job.salary,
                    indu.id, induTransFr.shortName, induTransFr.name, induTransFr.slug, induTransFr.description, induTransFr.displayOrder, induTransEn.shortName,
                    induTransEn.name, induTransEn.slug, induTransEn.description, induTransEn.displayOrder, job.postalCode, job.city, job.province,
                    job.benefits,job.displayEndedAt,job.schedule, jt.name,job.shift,job.startDate,job.confidential, job.applyExternalUrlFr, job.applyExternalUrlEn, job.deleted, job.language,
                    job.mobility, job.applyExternalEmail, job.referenceNumber, job.broadcastStatus, job.confidentialNameFr, job.confidentialNameEn, job.displayed, job.scheduleDiffusionDate,
                    cont.id, cont.firstName, cont.lastName, parentProfile.id, parentProfile.nameFr, parentProfile.nameEn, parentProfile.descriptionFr, parentProfile.descriptionEn, parent.profilId, parent.corpoId, job.cityId,job.regionId, jobdraft.id, jobdraft.deletedAt, job.displayedAt)'
            );
        $queryBuilder->from('App\Entity\Job','job')
            ->leftJoin('job.translation','transFr','with','transFr.languageCode = :langCodeFr')
            ->leftJoin('job.translation','transEn','with','transEn.languageCode = :langCodeEn')
            ->leftJoin('job.industry', 'indu')
            ->leftJoin('indu.translation', 'induTransFr', 'with', 'induTransFr.languageCode = :langCodeFr')
            ->leftJoin('indu.translation', 'induTransEn', 'with', 'induTransEn.languageCode = :langCodeEn')
            ->leftJoin('job.jobJobType', 'jjt', 'with', $queryBuilder->expr()->in(
                'jjt.jobType',
                $this->getEntityManager()->createQueryBuilder()
                    ->select('jt2.id')
                    ->from('App\Entity\JobType', 'jt2')
                    ->where('jt2.name IN (:types)')
                    ->getDQL()
            ))
            ->leftJoin('jjt.jobType', 'jt')
            ->leftJoin('job.contact', 'cont')
            ->leftJoin('cont.parentGroup', 'parent')
            ->leftJoin('App\Entity\Employer', 'parentProfile','with','parent.profilId = parentProfile.id')
            ->leftjoin('App\Entity\JobDraft', 'jobdraft', 'with', 'jobdraft.jobId = job.id AND jobdraft.deletedAt IS NULL')
            // add where clause on a case by case basis
            ->setParameter('langCodeFr','fr')
            ->setParameter('langCodeEn','en')
            ->setParameter('types', JobType::SCHEDULE_TYPES);


        return $queryBuilder;
    }

    public function fetchJobInfo($jobId):JobDTO{
        $queryBuilder = $this->jobDtoQuery();
        $queryBuilder->where('job.id = :jobId')
            ->setParameter('jobId',$jobId)
            ->setMaxResults(1);

        return $queryBuilder->getQuery()->getSingleResult();
    }

    /**
     * @deprecated will need improvement when fetching job list
     */
    public function fetchJobList(Criteria $criteria):CollectionDTO{
        $queryBuilder = $this->jobDtoQuery();

        // add search criteria
        $queryBuilder->addCriteria($criteria);

        $query = $queryBuilder->getQuery();

        return new CollectionDTO($query->getResult());
    }
}
