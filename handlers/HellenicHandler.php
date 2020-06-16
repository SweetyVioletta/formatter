<?php

namespace app\handlers;

use app\helpers\XlsHelper;

class HellenicHandler extends Handler
{
    /**
     * @param XlsHelper $helper
     *
     * @return bool
     */
    public function parseAndWrite($helper): bool
    {
        $newFileName = $helper->getConvertedFileName($this->fileName);
        $rows = $helper->getRows($this->fileName);
        array_shift($rows);
        $helper->writeHeaderToConvertedFile($this->getFormattedHeader(), $newFileName);
        foreach ($rows as $row) {
            $helper->writeRowToConvertedFile(trim(implode(',', $this->getFormattedRow($row))) . "\n", $newFileName);
        }
        return true;
    }

    private function getFormattedHeader(): string
    {
        return "ACCOUNT NO,PERIOD,CURRENCY,DATE,DESCRIPTION,PAYEE,DEBIT_CREDIT,VALUE DATE,BALANCE\n";
    }

    private function getFormattedRow(array $row)
    {
        $newRow = $row;
        $newRow[4] = $this->formatDescription($row[4]);
        $newRow[5] = $this->formatPayee($row[4]);
        $newRow[6] = $this->formatDebitCredit($row[5], $row[6]);
        $newRow[8] = str_replace(',', '.', $row[8]);
        array_walk($newRow, function (&$element) {
            $element = trim($element);
        });
        return $newRow;
    }

    private function formatDescription(string $desc)
    {
        return substr($desc, 0, 14);
    }

    private function formatPayee(string $desc)
    {
        return substr($desc, 15);
    }

    private function formatDebitCredit($debit, $credit)
    {
        if ('' !== $debit && ($debit > 0)) {
            return str_replace('--', '-', '-' . str_replace(',', '', $debit));
        }
        return str_replace(',', '', $credit);
    }
}