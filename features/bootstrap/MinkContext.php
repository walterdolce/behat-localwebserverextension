<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Defines application features from the specific context.
 */
class MinkContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    /**
     * @When my context connects to the local webserver
     */
    public function myContextConnectsToTheLocalWebserver()
    {
        $this->visitPath('/');
    }

    /**
     * @Then I should receive some content
     */
    public function iShouldReceiveSomeContent()
    {
        $this->assertSession()->pageTextContains('It works!');
    }
}

