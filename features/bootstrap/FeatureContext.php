<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

class FeatureContext implements Context, SnippetAcceptingContext
{
    private $content;

    /**
     * @When my context connects to the local webserver
     */
    public function myContextConnectsToTheLocalWebserver()
    {
        $this->content = @file_get_contents('http://localhost:8080');
    }

    /**
     * @Then I should receive some content
     */
    public function iShouldReceiveSomeContent()
    {
        if (!$this->content) {
            throw new Exception('Received no response from http://localhost:8080');
        }
    }

    /**
     * @Then the Selenium server starts
     */
    public function theSeleniumServerStarts()
    {
        $content = @file_get_contents('http://localhost:4444');
        if (!$content) {
            throw new Exception('Received no response from http://localhost:4444');
        }
    }
}

