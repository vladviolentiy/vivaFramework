<?php

namespace VladViolentiy\VivaFramework;

use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;

class Req
{
    private readonly Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param non-empty-string $param
     * @return string
     */
    public function get(string $param):string{
        /** @var string $line */
        $line = $this->request->get($param);
        return trim($line);
    }

    /**
     * @param non-empty-string $key
     * @return string
     */
    public function getServer(string $key):string{
        /** @var string $line */
        $line = $this->request->server->get($key);
        return trim($line);
    }

    /**
     * @param non-empty-string $key
     * @return FileBag|null
     */
    public function getFile(string $key):?FileBag{
        /** @var FileBag|null $file */
        $file = $this->request->files->get($key);
        return $file;
    }
}