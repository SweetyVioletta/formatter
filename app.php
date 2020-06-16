<?php
namespace app;

require 'vendor/autoload.php';
include 'FileFactory.php';
include 'HelperFactory.php';

$factory = new FileFactory();

foreach (scandir('sources') as $fileName) {
    if (in_array($fileName, ['.', '..'])) {
        continue;
    }
    if ('.' === $fileName[0]) {
        continue;
    }
    $handler = $factory->createHandler($fileName);
    if (null === $handler) {
        echo "$fileName: there is no rules for this file. File is skipped \n";
        continue;
    }
    $result = $handler->execute();
    echo $result ? "$fileName: OK \n" : "$fileName: something went wrong \n";
}
