Feature: JobUpdate
	In order to update for a job
	I need to be able to go to see results from the core services

Scenario: update a single job in core-service
	Given I use internal api request at "http://YOUR DOMAIN HERE/jsonrpc"
	When I update a job "1597859" with title "best job in the world"
	Then I should my job "1597859" with title "best job in the world"