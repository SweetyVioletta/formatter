<?php
namespace app\handlers;

use app\HelperFactory;

abstract class Handler
{
    public $fileName;

    /**
     * Handler constructor.
     *
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function execute(): bool
    {
        $extension = $this->getFileExtension();
        $fileHelper = (new HelperFactory())->getHelper($extension);
        return $this->parseAndWrite($fileHelper);
    }

    private function getFileExtension(): string
    {
        return substr($this->fileName, strripos($this->fileName, '.') + 1);
    }

    abstract protected function parseAndWrite($helper): bool;
}
