<?php

function test()
{
    foreach (scandir('formatted') as $fileName) {
        if (in_array($fileName, ['.', '..'])) {
            continue;
        }
        if (false === compare($fileName)) {
            echo "Error $fileName \n";
            return;
        }
    }
    echo "OK \n";
}

function compare(string $fileName)
{
    $testFile = str_replace('Source', 'Converted', $fileName);
    $command = `diff 'formatted/$fileName' 'examples/$testFile'`;
    if (null === $command) {
        return true;
    }
    echo "$command \n";
    return false;
}
