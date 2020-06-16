<?php

namespace app\helpers;

class TxtHelper extends Helper
{

    public function getRows(string $fileName): array
    {
        return file(__DIR__ . '/../sources/' .  $fileName);
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
