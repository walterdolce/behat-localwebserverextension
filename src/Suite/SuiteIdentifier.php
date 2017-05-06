<?php

namespace WalterDolce\Behat\SeleniumServerExtension\Suite;

use Behat\Testwork\Suite\Suite;

/**
 * Class SuiteIdentifier
 * @package WalterDolce\Behat\SeleniumServerExtension\Suite
 */
class SuiteIdentifier
{
    /**
     * @var array
     */
    private $suiteNames;

    /**
     * SuiteIdentifier constructor.
     * @param array $suiteNames
     */
    public function __construct(array $suiteNames)
    {
        $this->suiteNames = $suiteNames;
    }

    /**
     * @param Suite $suite
     * @return bool
     */
    public function suiteNeedsServer(Suite $suite)
    {
        if (!count($this->suiteNames)) {
            return true;
        }

        return in_array($suite->getName(), $this->suiteNames);
    }
}

