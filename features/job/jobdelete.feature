Feature: JobDelete
	In order to delete a job
	I need to be able to go to see results from the core services

Scenario: delete a single job in core-service
	Given I use internal api request at "http://YOUR DOMAIN HERE/jsonrpc"
	When I delete job for id "1597859"
	Then My Job "1597859" should be deleted
	Then My Job "1597859" should not be in search results