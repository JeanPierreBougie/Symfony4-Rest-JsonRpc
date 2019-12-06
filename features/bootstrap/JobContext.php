<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


/**
 * Defines application features from the specific context.
 */
class JobContext implements Context
{
    private $httpClient = null;

    private $httpRequest = null;

    private $jsonRpcApiUrl = '';

    private $container = null;

    private $username = 'jb';
    private $password = 'jbuser';

    // private $jobInfoDto = null;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->httpClient = new Client(['auth' => [$this->username, $this->password]]);

      /*  $kernel = new App\Kernel('test', true);
        $kernel->boot();
        $this->container = $kernel->getContainer();*/
    }



    /**
     * @Given I use internal api request at :arg1
     */
    public function iUseInternalApiRequestAt($arg1)
    {
        $this->jsonRpcApiUrl=$arg1;
    }

    /**
     * @When I read job for id :arg1
     */
    public function iReadJobForId($arg1)
    {
        //$this->jobInfoDto = $this->container->get('job.v1.bo.jsonrpc')->fetchJobInfo($arg1);

        $this->httpRequest = new Request('POST',$this->jsonRpcApiUrl,array(),'{"jsonrpc":"2.0","method":"job.v1.bo:fetchJobInfo","params":["'.$arg1.'"],"id":"3"}');
    }

    /**
     * @Then I should see a job result :arg1
     */
    public function iShouldSeeAJobResult($arg1)
    {
        $response = $this->httpClient->send($this->httpRequest);

        $stdJsonResult = json_decode($response->getBody());
        if($arg1 != $stdJsonResult->result->id){
            throw new \Exception("Id missmatch");
        }
/*
        if($arg1 != $this->jobInfoDto->getId()){
            throw new \Exception("Id missmatch");
        }
*/
    }

    /**
     * @When I fetch job for id :arg1 that is missing
     */
    public function iFetchJobForIdThatIsMissing($arg1)
    {
        $this->httpRequest = new Request('POST',$this->jsonRpcApiUrl,array(),'{"jsonrpc":"2.0","method":"job.v1.bo:fetchJobInfo","params":["'.$arg1.'"],"id":"3"}');
    }

    /**
     * @Then I should not see a job result :arg1
     */
    public function iShouldNotSeeAJobResult($arg1)
    {
        $response = $this->httpClient->send($this->httpRequest);

        $stdJsonResult = json_decode($response->getBody());
        if(isset($stdJsonResult->result->id)){
            throw new \Exception("should not get any results");
        }
    }

    /**
     * @When I fetch job list for id :arg1
     */
    public function iFetchJobListForId($arg1)
    {
        $this->httpRequest = new Request('POST',$this->jsonRpcApiUrl,array(),'{"jsonrpc":"2.0","method":"job.v1.bo:fetchJobList","params":[{"id":["'.$arg1.'"]}],"id":2}');
    }

    /**
     * @Then I should see a job result :arg1 in list
     */
    public function iShouldSeeAJobResultInList($arg1)
    {
        $response = $this->httpClient->send($this->httpRequest);

        $stdJsonResult = json_decode($response->getBody());

        if(!isset($stdJsonResult->result[0]->id) || $stdJsonResult->result[0]->id !=$arg1){
            throw new \Exception("Job ID ".$arg1." should be in list");
        }
    }

    /**
     * @When I create a job :arg1
     */
    public function iCreateAJob($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then My job :arg1 should be visible
     */
    public function myJobShouldBeVisible($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I update a job :arg1 with title :arg2
     */
    public function iUpdateAJobWithTitle($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then I should my job :arg1 with title :arg2
     */
    public function iShouldMyJobWithTitle($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @When I delete job for id :arg1
     */
    public function iDeleteJobForId($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then My Job :arg1 should be deleted
     */
    public function myJobShouldBeDeleted($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then My Job :arg1 should not be in search results
     */
    public function myJobShouldNotBeInSearchResults($arg1)
    {
        throw new PendingException();
    }
}
