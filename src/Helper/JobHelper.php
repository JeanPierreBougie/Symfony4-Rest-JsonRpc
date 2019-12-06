<?php
/**
 * Created by PhpStorm.
 * User: jpbougie
 * Date: 2017-12-13
 * Time: 09:56
 */

namespace App\Helper;
use Doctrine\Common\Collections\Criteria;

class JobHelper
{
    private $arrAvailableSearchCriteria = ["id","title","region","createdAt","modifiedAt","limit","offset"];
    private $arrAvailableJobMaintaskSearchCriteria = ["id","jobId","limit","offset"];

    /**
     * transform search parameters to criteria object
     * @param array $arrSearchCriteria
     * @return Criteria
     */
    public static function getSearchCriteriaFromParameters(array $arrSearchCriteria=array()){
        $searchCriteria = new Criteria();
        $expression = Criteria::expr();

        self::addLimitOffset($arrSearchCriteria,$searchCriteria);

        if(isset($arrSearchCriteria['id'])){

            $searchCriteria->andWhere($expression->in('id', (array)$arrSearchCriteria['id']));


            /*$expr = Criteria::expr();
            $criteria = Criteria::create();
            $criteria->where($expr->gte('start', $start));
            $criteria->andWhere($expr->lte('end', $end);
            */
            //$searchCriteria->setMaxResults($arrSearchCriteria['limit']);
        }

        if(isset($arrSearchCriteria['region'])){
            $searchCriteria->andWhere($expression->in('regionId', (array)$arrSearchCriteria['region']));
        }


        return $searchCriteria;
    }

    public static function getSearchMaintaskCriteriaFromParameters(array $arrSearchCriteria=array()){
        $searchCriteria = new Criteria();
        $expression = Criteria::expr();

        self::addLimitOffset($arrSearchCriteria,$searchCriteria);

        if(isset($arrSearchCriteria['jobId'])){
            $searchCriteria->andWhere($expression->in('jmt.job', (array)$arrSearchCriteria['jobId']));
        }


        return $searchCriteria;
    }

    public static function getSearchJobTypeCriteriaFromParameters(array $arrSearchCriteria=array()){
        $searchCriteria = new Criteria();
        $expression = Criteria::expr();

        self::addLimitOffset($arrSearchCriteria,$searchCriteria);

        if(isset($arrSearchCriteria['jobId'])){
            $searchCriteria->andWhere($expression->in('jjt.job', (array)$arrSearchCriteria['jobId']));
        }


        return $searchCriteria;
    }


    public static function getSearchRequirementsCriteriaFromParameters(array $arrSearchCriteria=array()){
        $searchCriteria = new Criteria();
        $expression = Criteria::expr();

        self::addLimitOffset($arrSearchCriteria,$searchCriteria);

        if(isset($arrSearchCriteria['jobId'])){
            $searchCriteria->andWhere($expression->in('req.job', (array)$arrSearchCriteria['jobId']));
        }


        return $searchCriteria;
    }

    public static function getSearchAbilitiesCriteriaFromParameters(array $arrSearchCriteria=array()){
        $searchCriteria = new Criteria();
        $expression = Criteria::expr();

        self::addLimitOffset($arrSearchCriteria,$searchCriteria);

        if(isset($arrSearchCriteria['jobId'])){
            $searchCriteria->andWhere($expression->in('ab.job', (array)$arrSearchCriteria['jobId']));
        }
        return $searchCriteria;
    }

    public static function getSearchContactInfoFromParameters(array $arrSearchCriteria=array()){
        $searchCriteria = new Criteria();
        $expression = Criteria::expr();

        self::addLimitOffset($arrSearchCriteria,$searchCriteria);

        if(isset($arrSearchCriteria['jobId'])){
            $searchCriteria->andWhere($expression->in('cc.job', (array)$arrSearchCriteria['jobId']));
        }
        return $searchCriteria;
    }

    private static function addLimitOffset(array $arrSearchCriteria,$searchCriteria){
        if(isset($arrSearchCriteria['limit'])){
            $searchCriteria->setMaxResults($arrSearchCriteria['limit']);
        }

        if(isset($arrSearchCriteria['offset'])){
            $searchCriteria->setFirstResult($arrSearchCriteria['offset']);
        }
    }

    /**
     * show the available search criteria
     * @return array
     */
    public function getAvailableSearchCriteria(){
        return $this->arrAvailableSearchCriteria;
    }
}