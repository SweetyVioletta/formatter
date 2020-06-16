<?php

namespace app\handlers;

use app\helpers\TxtHelper;

class FIBankHandler extends Handler
{
    /**
     * @param TxtHelper $helper
     *
     * @return bool
     */
    public function parseAndWrite($helper): bool
    {
        $newFileName = $helper->getConvertedFileName($this->fileName);
        $rows = $helper->getRows($this->fileName);
        $header = array_shift($rows);
        if (false === $helper->writeHeaderToConvertedFile($this->refactorHeader($header), $newFileName)) {
            return false;
        }
        foreach ($rows as $row) {
            $newRow = $this->formatRow($row);
            if (false === $helper->writeRowToConvertedFile($newRow, $newFileName)) {
                return false;
            }
        }
        return true;
    }

    private function refactorHeader(string $header)
    {
        return str_replace('debit,credit', 'debit_credit', $header);
    }

    private function formatRow(string $row)
    {
        $rowArr = explode(',', $row);
        $debit = $rowArr[3];
        $credit = $rowArr[4];
        $strForReplace = "$debit,$credit";
        $debitCredit = $debit !== '' ? -1 * (float) $debit : (float) $credit;
        return str_replace($strForReplace, $debitCredit, $row);
    }
}
