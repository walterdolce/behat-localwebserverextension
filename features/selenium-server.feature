Feature: Selenium server is provisioned and runs automatically
  In order to test and develop more quickly
  As a developer
  I should not have to remember to download and run the selenium server when executing tests

  Scenario: Starting the Selenium server locally
    When my context connects to the local webserver
    Then the Selenium server starts
    And I should receive some content