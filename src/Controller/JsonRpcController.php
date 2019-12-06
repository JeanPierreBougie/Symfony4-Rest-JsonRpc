<?php
/**
 * Exemple of jsonRPC Call
 *
 * URI: /jsonrpc
 * Method: Post
 * Data BATCH:
    [{"jsonrpc": "2.0", "method": "job.businessprocess:fetchJobInfo", "params": [42], "id": 1},
    {"jsonrpc": "2.0", "method": "job.businessprocess:fetchJobInfo", "params": [42], "id": 1}]
 * Data Single:
    {"jsonrpc": "2.0", "method": "job.businessprocess:fetchJobInfo", "params": [42], "id": 1}
 * Data with dimension as second params
    {"jsonrpc":"2.0","method":"job.businessprocess:fetchJobInfo","params":[[42],["maintask"]],"id":1}
 */

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonRpcController extends Controller
{

    const PARSE_ERROR = -32700;
    const INVALID_REQUEST = -32600;
    const METHOD_NOT_FOUND = -32601;
    const INVALID_PARAMS = -32602;
    const INTERNAL_ERROR = -32603;


    /**
     * @return Response
     */
    public function executeAction()
    {

        $arrReturn = array();
        //$translator = $this->container->get('translator.default');

        $strRequestContent = Request::createFromGlobals()->getContent();

        $request = json_decode($strRequestContent, true);
        $requestMulti = array();
        $blnMultiRequest = false;

        if(isset($request['jsonrpc'])){
            $requestMulti[]=$request;
        }else{
            $requestMulti = $request;
            $blnMultiRequest = true;
        }


        foreach($requestMulti as $requestRpc){
            $request = new Request(array(),array(),array(),array(),array(),array(),json_encode($requestRpc));

            $httpResponse = $this->execute($request);

            $strJsonResponse = $httpResponse->getContent();
            $jsonResponse = json_decode($strJsonResponse);
            if(isset($jsonResponse->error) && isset($jsonResponse->error->data)){

                $stdJsonData = json_decode(stripslashes($jsonResponse->error->data));
                if(is_object($stdJsonData)){
                    $jsonResponse->error->data =$stdJsonData;
                }


                //$arrErrorMessage = array('email'=>'test','nick'=>'test2');
                /*$arrErrorMessage = explode("\r\n",$jsonResponse->error->data);

                foreach($arrErrorMessage as $intKey=>$strValue){
                       $arrErrorMessage[$intKey] =$translator->trans(trim($strValue),array(),'validators');
                       $arrErrorMessage[$intKey] =$translator->trans(trim($arrErrorMessage[$intKey]),array(),'errors');
                }*/

                //$jsonResponse->error->data = $arrErrorMessage;
                $httpResponse->setContent(json_encode($jsonResponse));
            }

            $arrReturn[] = $httpResponse;
        }

        if($blnMultiRequest){
            $arrResponseContent = array();
            foreach($arrReturn as $request){
                $arrResponseContent[]=$request->getContent();
            }

            return new Response('['.implode(',',$arrResponseContent).']',200, array('Content-Type' => 'application/json'));
        }else{
            return $arrReturn[0];
        }

    }

    /**
     * @param Request $httprequest
     * @return Response
     */
    public function execute(Request $httprequest)
    {

        $json = $httprequest->getContent();
        $request = json_decode($json, true);
        $requestId = (isset($request['id']) ? $request['id'] : null);

        if ($request === null) {
            return $this->getErrorResponse(self::PARSE_ERROR, null);
        } elseif (!(isset($request['jsonrpc']) && isset($request['method']) && $request['jsonrpc'] == '2.0')) {
            return $this->getErrorResponse(self::INVALID_REQUEST, $requestId);
        }

        list($servicename,$method) = explode(':',$request['method']);

        if(empty($servicename)||empty($method)){
            return $this->getErrorResponse(self::METHOD_NOT_FOUND, $requestId);
        }

        try {
            $service = $this->container->get($servicename.'.jsonrpc');
        } catch (ServiceNotFoundException $e) {
            return $this->getErrorResponse(self::METHOD_NOT_FOUND, $requestId);
        }
        $params = (isset($request['params']) ? $request['params'] : array());

        if (is_callable(array($service, $method))) {
            $r = new \ReflectionMethod($service, $method);
            $rps = $r->getParameters();

            if (is_array($params)) {
                if (!(count($params) >= $r->getNumberOfRequiredParameters()
                    && count($params) <= $r->getNumberOfParameters())
                ) {
                    return $this->getErrorResponse(self::INVALID_PARAMS, $requestId,
                        sprintf('Number of given parameters (%d) does not match the number of expected parameters (%d required, %d total)',
                            count($params), $r->getNumberOfRequiredParameters(), $r->getNumberOfParameters()));
                }

            }
            if ($this->isAssoc($params)) {
                $newparams = array();
                foreach ($rps as $i => $rp) {
                    /* @var \ReflectionParameter $rp */
                    $name = $rp->name;
                    if (!isset($params[$rp->name]) && !$rp->isOptional()) {
                        return $this->getErrorResponse(self::INVALID_PARAMS, $requestId,
                            sprintf('Parameter %s is missing', $name));
                    }
                    if (isset($params[$rp->name])) {
                        $newparams[] = $params[$rp->name];
                    } else {
                        $newparams[] = null;
                    }
                }
                $params = $newparams;
            }

            // correctly deserialize object parameters
            foreach ($params as $index => $param) {
                // if the json_decode'd param value is an array but an object is expected as method parameter,
                // re-encode the array value to json and correctly decode it using jsm_serializer
                if (is_array($param) && !$rps[$index]->isArray() && $rps[$index]->getClass() != null) {
                    $class = $rps[$index]->getClass()->getName();
                    $param = json_encode($param);
                    $params[$index] = $this->container->get('jms_serializer')->deserialize($param, $class, 'json');
                }
            }

            try {
                $result = call_user_func_array(array($service, $method), $params);
            } catch (\Exception $e) {
                return $this->getErrorResponse(self::INTERNAL_ERROR, $requestId, $e->getMessage());
            }

            $response = array('jsonrpc' => '2.0');
            $response['result'] = $result;
            $response['id'] = $requestId;

            $response = json_encode($response);


            return new Response($response, 200, array('Content-Type' => 'application/json'));
        } else {
            return $this->getErrorResponse(self::METHOD_NOT_FOUND, $requestId);
        }
    }


    protected function getError($code)
    {
        $message = '';
        switch ($code) {
            case self::PARSE_ERROR:
                $message = 'Parse error';
                break;
            case self::INVALID_REQUEST:
                $message = 'Invalid request';
                break;
            case self::METHOD_NOT_FOUND:
                $message = 'Method not found';
                break;
            case self::INVALID_PARAMS:
                $message = 'Invalid params';
                break;
            case self::INTERNAL_ERROR:
                $message = 'Internal error';
                break;
        }

        return array('code' => $code, 'message' => $message);
    }

    protected function getErrorResponse($code, $id, $data = null)
    {
        $response = array('jsonrpc' => '2.0');
        $response['error'] = $this->getError($code);

        if ($data != null) {
            $response['error']['data'] = $data;
        }

        $response['id'] = $id;

        return new Response(json_encode($response), 200, array('Content-Type' => 'application/json'));
    }


    /**
     * Finds whether a variable is an associative array
     *
     * @param $var
     * @return bool
     */
    protected function isAssoc($var)
    {
        return array_keys($var) !== range(0, count($var) - 1);
    }
}
