<?php

declare(strict_types=1);

$handle = fopen('php://stdin', 'r');

echo 'Enter test cases count: ';
$testCases = (int) fgets($handle);

while ($testCases-- > 0) {
    $input = getValidInputData($handle);

    $operator = $input[1];
    match ($operator) {
        '-', '+' => outputSubtractionOrAddition((int) $input[0], (int) $input[2], $operator),
        '*' => outputMultiplication((int) $input[0], (int) $input[2]),
    };
}

fclose($handle);

/**
 * @param resource $handle
 */
function getValidInputData(mixed $handle): array
{
    $input = [];
    $isValidInput = false;
    while (!$isValidInput) {
        echo 'Enter the expression: '; 
        $input = preg_split("/(\-|\+|\*)/", fgets($handle), flags: PREG_SPLIT_DELIM_CAPTURE);
        $isValidInput = isValidInput($input);
    }

    return $input;
}

function isValidInput(array $input): bool
{
    if (count($input) == 1) {
        printf("Unsupported arithmetic operation for this input: %s\n", $input[0]);
        return false;
    }

    if (count($input) > 3) {
        echo 'Incorrect input provided. Must be {number}{operator}{number}' . PHP_EOL;
        return false;
    }

    return true;
}

function outputSubtractionOrAddition(
    int $leftSideNumber,
    int $rightSideNumber,
    string $operator,
): void {
    $dashes = getBiggerNumberDashes($leftSideNumber, $rightSideNumber);
    $dashesLength = strlen($dashes);
    $result = $operator === '-'
        ? $leftSideNumber - $rightSideNumber
        : $leftSideNumber + $rightSideNumber;

    printf(
        "%*d\n%*s%d\n%*s\n%*d\n\n",
        $dashesLength,
        $leftSideNumber,
        $dashesLength - strlen((string) $rightSideNumber),
        $operator,
        $rightSideNumber,
        $dashesLength,
        $dashes,
        $dashesLength,
        $result,
    );
}

function outputMultiplication(int $leftSideNumber, int $rightSideNumber): void
{
    $result = $leftSideNumber * $rightSideNumber;
    $resultDashesLength = strlen((string) $result);

    printf(
        "%*d\n%*s%d\n%*s\n",
        $resultDashesLength,
        $leftSideNumber,
        $resultDashesLength - strlen((string) $rightSideNumber),
        '*',
        $rightSideNumber,
        $resultDashesLength,
        getBiggerNumberDashes($leftSideNumber, $rightSideNumber),
    );

    $rightSideDigits = array_reverse(
        array_map('intval', str_split((string) $rightSideNumber))
    );

    foreach ($rightSideDigits as $index => $digit) {
        printf(
            "%*d\n",
            $resultDashesLength - $index,
            $digit * $leftSideNumber,
        );
    }

    $resultDashes = str_repeat('-', $resultDashesLength);

    printf("%s\n%d\n", $resultDashes, $result);
}

function getBiggerNumberDashes(int $leftSideNumber, int $rightSideNumber): string
{
    $biggerNumber = $leftSideNumber > $rightSideNumber ? $leftSideNumber : $rightSideNumber;
    $additionalDashesCount = strlen((string) $leftSideNumber) <= strlen((string) $rightSideNumber) ? 1 : 0;
    $dashesLength = strlen((string) $biggerNumber) + $additionalDashesCount;
    
    return str_repeat('-', $dashesLength);
}