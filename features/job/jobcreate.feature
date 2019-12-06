Feature: JobCreate
	In order to create a job
	I need to be able to go to see results from the core services

Scenario: create a single job in core-service
	Given I use internal api request at "http://YOUR DOMAIN HERE/jsonrpc"
	When I create a job "1597859"
	Then My job "1597859" should be visible