<?php

namespace PhpPsTest;


class PostscriptDocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $document = new PostscriptDocument();
        $document->render();
    }
}

