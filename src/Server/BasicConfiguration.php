<?php

namespace WalterDolce\Behat\SeleniumServerExtension\Server;

/**
 * Class BasicConfiguration
 * @package WalterDolce\Behat\SeleniumServerExtension\Server
 */
final class BasicConfiguration implements Configuration
{
    /**
     * @var
     */
    private $host;
    /**
     * @var
     */
    private $port;
    /**
     * @var
     */
    private $docroot;
    /**
     * @var null
     */
    private $router;

    /**
     * BasicConfiguration constructor.
     * @param $host
     * @param $port
     * @param $docroot
     * @param null $router
     */
    public function __construct($host, $port, $docroot, $router = Configuration::DEFAULT_ROUTER)
    {
        $this->host = $host;
        $this->port = $port;
        $this->docroot = $docroot;
        $this->router = $router;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getDocroot()
    {
        return $this->docroot;
    }

    /**
     * @return null
     */
    public function getRouter()
    {
        return $this->router;
    }
}
