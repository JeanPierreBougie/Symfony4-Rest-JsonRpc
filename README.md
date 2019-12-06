# Symfony4-Rest-JsonRpc
Simple proof of concept using JsonRpc and REST

## Framework: Symfony 4 Standard Edition

### Base installed from theses commands

    composer create-project symfony/skeleton jobboom-core "4.0.*" -s dev
    composer require annotations
    composer require --dev profiler
    composer require twig
    composer require logger
    composer require doctrine maker
    composer require doctrine/doctrine-bundle
    composer require validator
    composer require phpunit
    composer require --dev behat/behat
    composer require --dev behat/mink
    composer require --dev behat/mink-goutte-driver
    
Single Request:

    {"jsonrpc": "2.0", "method": "job.bo:fetchJobInfo", "params": [42], "id": 1}

Batch Request:

    [{"jsonrpc": "2.0", "method": "job.bo:fetchJobInfo", "params": [42], "id": 1},
    {"jsonrpc": "2.0", "method": "job.bo:fetchJobInfo", "params": [42], "id": 1}]
    
Dimension Request:

    [{"jsonrpc":"2.0","method":"job.bo:fetchJobInfo","params":[42,["maintask","requirement"]],"id":1},
    {"jsonrpc":"2.0","method":"job.bo:fetchSupportedDimensions","params":[],"id":1}]    
    
Search param as jsonObject

    {"jsonrpc":"2.0","method":"job.v1.bo:fetchJobList","params":[{"limit":"2","offset":"10"}],"id":2}
    {"jsonrpc":"2.0","method":"job.v1.bo:fetchJobList","params":[{"id":["000037","000038"]}],"id":2}
    {"jsonrpc":"2.0","method":"job.v1.bo:fetchJobList","params":[{"region":["87","57"],"limit":"100"}],"id":2}    
    
### REST Call supported

Sample `GET` URI:
    /v1/job/?id[]=000047&id[]=000048&limit=1&offset=1
    /v1/job/000047
    /v1/job/000047?dimensions=maintask
    /v1/job/?region[]=87&region=57&limit=100&offset=80
    
### Design Pattern

- Manager Renamed to DAO: https://www.tutorialspoint.com/design_pattern/data_access_object_pattern.htm
- DTO Pattern: https://www.tutorialspoint.com/design_pattern/transfer_object_pattern.htm
- Mediator Pattern: https://www.tutorialspoint.com/design_pattern/mediator_pattern.htm
- BusinessObject BO Pattern: http://www.corej2eepatterns.com/BusinessObject.htm 
- Business Delegate Pattern: https://www.tutorialspoint.com/design_pattern/business_delegate_pattern.htm
- Chain of Responsibility Pattern: https://www.tutorialspoint.com/design_pattern/chain_of_responsibility_pattern.htm
- Iterator Pattern: https://www.tutorialspoint.com/design_pattern/iterator_pattern.htm

### Other References

- GA Dimensions / Metrics: https://developers.google.com/analytics/devguides/reporting/core/dimsmets#cats=user
- GraphQL: http://graphql.org/learn/
- Doctrine Entities: https://symfony.com/doc/current/doctrine.html#creating-an-entity-class    
