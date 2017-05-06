<?php

namespace WalterDolce\Behat\SeleniumServerExtension\EventDispatcher;

use Behat\Testwork\EventDispatcher\Event\BeforeSuiteTested;
use Behat\Testwork\EventDispatcher\Event\ExerciseCompleted;
use Behat\Testwork\EventDispatcher\Event\SuiteTested;
use WalterDolce\Behat\SeleniumServerExtension\Server\ServerController;
use WalterDolce\Behat\SeleniumServerExtension\Suite\SuiteIdentifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ServerSubscriber
 * @package WalterDolce\Behat\SeleniumServerExtension\EventDispatcher
 */
final class ServerSubscriber implements EventSubscriberInterface
{
    /**
     * @var ServerController
     */
    private $serverController;

    /**
     * @var bool
     */
    private $isStarted = false;

    /**
     * @var SuiteIdentifier
     */
    private $suiteIdentifier;

    /**
     * ServerSubscriber constructor.
     * @param ServerController $serverController
     * @param SuiteIdentifier $suiteIdentifier
     */
    public function __construct(ServerController $serverController, SuiteIdentifier $suiteIdentifier)
    {
        $this->serverController = $serverController;
        $this->suiteIdentifier = $suiteIdentifier;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            SuiteTested::BEFORE => 'startServer',
            ExerciseCompleted::BEFORE_TEARDOWN => 'stopServer'
        ];
    }

    /**
     * @param BeforeSuiteTested $suiteEvent
     */
    public function startServer(BeforeSuiteTested $suiteEvent)
    {
        $suite = $suiteEvent->getSuite();

        if ($this->suiteIdentifier->suiteNeedsServer($suite) && !$this->isStarted) {
            $this->serverController->startServer();
            $this->isStarted = true;
        }
    }

    public function stopServer()
    {
        if ($this->isStarted) {
            $this->serverController->stopServer();
        }
    }
}

