<?php

namespace VladViolentiy\VivaFramework\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use VladViolentiy\VivaFramework\Req;

class ReqTest extends TestCase
{
    public function testSimplePost(): void
    {
        $request = new Request();
        $request->request->set('value', '123');
        $request->request->set('text', 'textValue');
        $req = new Req($request);
        $this->assertEquals('123', $req->get('value'));
        $this->assertEquals('textValue', $req->get('text'));
    }

    public function testServerHeader(): void
    {
        $request = new Request();
        $request->server->set('auth', 'testAuth');
        $req = new Req($request);
        $this->assertEquals('testAuth', $req->getServer('auth'));
    }

    public function testInputFile(): void
    {
        $request = new Request();
        $request->files->set('file', new UploadedFile(__DIR__ . '/ReqTest.php', 'ReqTest.php'));
        $req = new Req($request);
        $this->assertInstanceOf(UploadedFile::class, $req->getFile('file'));
    }

    public function testExtractFromJson(): void
    {
        $jsonPayload = '{"key": "value"}';
        $request = Request::create(
            '/path',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $jsonPayload,
        );
        $req = new Req($request);
        $this->assertEquals('value', $req->get('key'));
        $this->assertEquals('value', $req->getArray()['key']);
    }
}
