<?php

namespace PhpPsTest;


use PHPUnit\Framework\TestCase;

class PostscriptDocumentTest extends TestCase
{
    public function testRender()
    {
        $document = new PostscriptDocument();
        $document->render();
    }
}

