<?php

namespace app\helpers;

class Helper
{
    public function getConvertedFileName(string $fileName)
    {
        return str_replace('Source', 'Converted', str_replace('file ', '', $fileName));
    }
}
