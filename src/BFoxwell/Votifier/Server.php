<?php namespace BFoxwell\Votifier;

use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use React\EventLoop\LibEventLoop;
use React\EventLoop\LoopInterface;
use React\EventLoop\StreamSelectLoop;
use React\Socket\Server as ReactServer;

class Server
{
    /**
     * Component
     *
     * @var \Ratchet\MessageComponentInterface
     */
    protected $component;

    /**
     * Port
     *
     * @var integer
     */
    protected $port;

    /**
     * Address
     *
     * @var string
     */
    protected $address;

    /**
     * Server
     *
     * @param MessageComponentInterface $component
     * @param $port
     * @param $address
     */
    public function __construct(MessageComponentInterface $component, $port, $address)
    {
        $this->component = $component;
        $this->port = $port;
        $this->address = $address;
    }

    /**
     * Start Server
     */
    public function start()
    {
        $loop = $this->createLoop();

        $socket = $this->createSocket($loop, $this->port, $this->address);

        $ioServer = new IoServer($this->component, $socket, $loop);

        $this->logStart();

        $ioServer->run();
    }

    /**
     * Create Socket
     *
     * @param LoopInterface $loop
     * @param $port
     * @param $address
     * @return Server
     */
    protected function createSocket(LoopInterface $loop, $port, $address)
    {
        $socket = new ReactServer($loop);

        $socket->listen($port, $address);

        return $socket;
    }

    /**
     * Create Event Loop
     *
     * @return LibEventLoop|StreamSelectLoop
     */
    protected function createLoop()
    {
        if (function_exists('event_base_new'))
        {
            return new LibEventLoop();
        }

        return new StreamSelectLoop();
    }

    /**
     * Log start time, address, and port.
     */
    protected function logStart()
    {
        $time = date('Y-m-d h:i:s');

        echo "[$time] \e[1;32mServer initialized on $this->address:$this->port\e[0m", PHP_EOL;
    }
} 