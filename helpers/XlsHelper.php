<?php

namespace app\helpers;

use PhpOffice\PhpSpreadsheet\Reader\Xls;

class XlsHelper extends Helper
{
    public function getRows(string $fileName)
    {
        $reader = new Xls();
        $spredSheet = $reader->load(__DIR__ . '/../sources/' . $fileName);
        return $spredSheet->getActiveSheet()->toArray();
    }

    public function getConvertedFileName(string $fileName)
    {
        return str_replace('.xls', '.csv', parent::getConvertedFileName($fileName));
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