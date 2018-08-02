<?php

namespace WalterDolce\Behat\SeleniumServerExtension\Server;

/**
 * Interface ServerController
 * @package WalterDolce\Behat\LocalWebserverExtension\Server
 */
interface ServerController
{
    /**
     * @return mixed
     */
    public function startServer();

    /**
     * @return mixed
     */
    public function stopServer();

    /**
     * @return boolean
     */
    public function isStarted();
}

