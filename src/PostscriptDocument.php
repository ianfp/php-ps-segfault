<?php

namespace PhpPsTest;

/**
 * Class for creating postscript documents.
 */
class PostscriptDocument
{
    protected $psLabel = null;
    private $tempFilename = null;

    /**
     * @return string
     *  The postscript file data
     */
    public function render()
    {
        $this->createDocument();
        $this->renderDocument();
        $psdata = $this->closeDocument();
        return $psdata;
    }

    protected function renderDocument()
    {
        $this->beginPage();
        $this->endPage();
    }

    protected function getPageHeight()
    {
        return 100;
    }

    protected function getPageWidth()
    {
        return 100;
    }

    private function createDocument()
    {
        if (!extension_loaded("ps")) {
            throw new \RuntimeException('Extension "ps" is not installed or loaded');
        }

        $this->tempFilename = tempnam('/tmp', 'LBL_');
        $this->psLabel = ps_new();

        ps_set_info($this->psLabel,
            "BoundingBox",
            sprintf("0 0 %s %s", $this->getPageWidth(), $this->getPageHeight())
        );
        ps_set_parameter($this->psLabel, "warning", "true");

        ps_open_file($this->psLabel, $this->tempFilename);
    }

    private function closeDocument()
    {
        ps_close($this->psLabel);
        $psdata = file_get_contents($this->tempFilename);
        ps_delete($this->psLabel);
        $this->psLabel = null;
        unlink($this->tempFilename);
        $this->tempFilename = null;
        return $psdata;
    }

    protected function beginPage()
    {
        ps_begin_page($this->psLabel, $this->getPageWidth(), $this->getPageHeight());
        $this->setCoordinateOrientation();
    }

    protected function inchToPoint($inches)
    {
        return (int) round($inches * 72);
    }

    protected function setCoordinateOrientation()
    {
        /* override */
    }

    protected function setTextPosition($x, $y)
    {
        ps_set_text_pos($this->psLabel, $x, $y);
    }

    protected function writeText($text)
    {
        try {
            ps_show($this->psLabel, utf8ToAscii($text));
        } catch (\ErrorException $ex) {
            $this->handlePsException($ex);
        }
    }

    protected function handlePsException(\ErrorException $ex)
    {
        /* Really, who cares about missing ligatures? */
        if (stripos($ex->getMessage(), 'ligature') === false) {
            throw $ex;
        }
    }

    protected function continueText($text)
    {
        try {
            ps_continue_text($this->psLabel, utf8ToAscii($text));
        } catch (\ErrorException $ex) {
            $this->handlePsException($ex);
        }
    }

    protected function setPsFont(PostscriptFont $font)
    {
        $fontId = ps_findfont($this->psLabel, $font->getPathWithoutExtension(), null, 1);
        if (!$fontId) throw new \InvalidArgumentException(sprintf(
            "Unable to load font '%s'", $font->getName()
        ));
        ps_setfont($this->psLabel, $fontId, $font->getSize());
    }

    protected function endPage()
    {
        ps_end_page($this->psLabel);
    }
}


function main()
{
    $doc = new PostscriptDocument();
    $doc->render();
}

main();
