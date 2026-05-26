<?php

declare(strict_types=1);

$handle = fopen('php://stdin', 'r');

echo 'Enter test cases count: ';
$testCases = (int) fgets($handle);

while ($testCases-- > 0) {
	[$stallCount, $cowsCount] = getValidInputData($handle);

	$availableStalls = getSortedAvailableStalls($stallCount, $handle);
	
	$left = 1;
	$right = $availableStalls[$stallCount-1] - $availableStalls[0];
	$answerDistance = 0;
	
	while ($left <= $right) {
		$mid = intdiv($left + $right, 2);
		
		if (canPlaceCows($availableStalls, $cowsCount, $mid)) {
			$answerDistance = $mid;
			$left = $mid + 1;
		} else {
			$right = $mid - 1;
		}
	}
	
	printf("The minimal distance is: %d\n", $answerDistance);
}

fclose($handle);

/**
 * @param resource $handle
 */
function getValidInputData(mixed $handle): array
{
	$stallCount = $cowsCount = 0;
	$isInputValid = false;
	while (!$isInputValid) {
		echo 'Enter stall count and cows count, separated by space: ';
		[$stallCount, $cowsCount] = explode(' ', fgets($handle));
		$stallCount = (int) $stallCount;
		$cowsCount = (int) $cowsCount;

		$isInputValid = isInputValid($stallCount, $cowsCount);
	}

	return [$stallCount, $cowsCount];
}

function isInputValid(int $stallCount, int $cowsCount): bool
{
	if ($stallCount < 2 || $stallCount > 100000) {
		echo 'Provided stall count is out of bounds (2 <= N <= 100000)' . PHP_EOL;
		return false;
	}

	if ($cowsCount < 2 || $cowsCount > $stallCount) {
		echo 'Provided cows count is out of bounds (2 <= C <= N, where N - stall count)' . PHP_EOL;
		return false;
	}

	return true;
}

/**
 * @param resource $handle
 */
function getSortedAvailableStalls(int $stallCount, mixed $handle): array
{
	$availableStalls = [];
	while ($stallCount-- > 0) {
		echo 'Enter stall position: ';
		$availableStalls[] = (int) fgets($handle);
	}
	
	sort($availableStalls);

	return $availableStalls;
}

function canPlaceCows(array $availableStalls, int $cowsCount, int $distance): bool
{
	$count = 1;
	$lastPosition = $availableStalls[0];
	
	for ($i = 1; $i < count($availableStalls); $i++) {
		if ($availableStalls[$i] - $lastPosition >= $distance) {
			$count++;
			$lastPosition = $availableStalls[$i];
			
			if ($count >= $cowsCount) {
				return true;
			}
		}
	}
	
	return false;
}