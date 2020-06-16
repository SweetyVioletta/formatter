<?php

namespace app\helpers;

use app\helpers\Helper;

class CsvHelper extends Helper
{
    public function initReading(string $fileName)
    {
        $handle = fopen(__DIR__ . '/../sources/' .  $fileName, 'r');
        ini_set('auto_detect_line_endings', true);
        return $handle;
    }

    public function initEndReading()
    {
        ini_set('auto_detect_line_endings', false);
    }

    public function getRow($handle)
    {
        return fgetcsv($handle);
    }

    public function writeHeaderToConvertedFile(string $header, string $fileName)
    {
        return file_put_contents(__DIR__ . '/../formatted/' .  $fileName, $header);
    }

    public function writeRowToConvertedFile(string $row, string $fileName)
    {
        return file_put_contents(__DIR__ . '/../formatted/' .  $fileName, $row, FILE_APPEND);
    }
}
