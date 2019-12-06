Feature: JobRead
	In order to search for a job
	I need to be able to go to see results from the core services

Scenario: fetch a single job in core-service
	Given I use internal api request at "http://YOUR DOMAIN HERE/jsonrpc"
	When I read job for id "1597859"
	Then I should see a job result "1597859"
	When I fetch job for id "1597859000000" that is missing
	Then I should not see a job result "1597859"

Scenario: fetch a single job in core-service from a list
	Given I use internal api request at "http://YOUR DOMAIN HERE/jsonrpc"
	When I fetch job list for id "1597859"
	Then I should see a job result "1597859" in list