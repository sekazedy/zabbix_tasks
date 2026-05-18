<?php

declare(strict_types=1);

$handle = fopen('php://stdin', 'r');

echo 'Enter data sets count: ';
$dataSets = (int) fgets($handle);

if ($dataSets < 1 || $dataSets > 10) {
    echo 'Data sets count is out of range (1 <= d <= 10)' . PHP_EOL;
    fclose($handle);
    exit();
}

while ($dataSets-- > 0) {
    [$elementsCount, $inversionsCount] = getValidInputData($handle);

    printf("The permutations number is %d\n", permutationsWithInversionsCount($elementsCount, $inversionsCount));
}

fclose($handle);

/**
 * @param resource $handle
 */
function getValidInputData(mixed $handle): array
{
    $elementsCount = $inversionsCount = 0;
    $isValidInput = false;
    while (!$isValidInput) {
        echo 'Enter elements count and inversions count, separated by space: ';
        [$elementsCount, $inversionsCount] = explode(' ', fgets($handle));
        $elementsCount = (int) $elementsCount;
        $inversionsCount = (int) $inversionsCount;

        $isValidInput = isValidInput($elementsCount, $inversionsCount);
    }

    return [$elementsCount, $inversionsCount];
}

function isValidInput(int $permutationsCount, int $inversionsCount): bool
{
    if ($permutationsCount < 1 || $permutationsCount > 12) {
        echo 'Incorrect permutations count: it must be in range from 1 to 12, inclusive' . PHP_EOL;
        return false;
    }

    if ($inversionsCount < 0 || $inversionsCount > 98) {
        echo 'Incorrect inversions count: it must be in range from 0 to 98, inclusive' . PHP_EOL;
        return false;
    }
    
    return true;
}

function permutationsWithInversionsCount(int $n, int $k): int
{
    if ($k === 0) {
        return 1;
    }

    $result = 0;
    for ($i = 0; $i <= min($k, $n-1); $i++) {
        $result += permutationsWithInversionsCount($n - 1, $k - $i);
    }

    return $result;
}