<?php

namespace VladViolentiy\VivaFramework;

use Symfony\Component\HttpFoundation\Request;

class Req
{
    private readonly Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get(string $param):string{
        /** @var string $line */
        $line = $this->request->get($param);
        return trim($line);
    }

    public function getServer(string $key):string{
        /** @var string $line */
        $line = $this->request->server->get($key);
        return trim($line);
    }
}