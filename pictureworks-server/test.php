<?php

/*
 * Complete the 'countingValleys' function below.
 *
 * The function is expected to return an INTEGER.
 * The function accepts following parameters:
 *  1. INTEGER steps
 *  2. STRING path
 */

function comparatorValue($a, $b, $d)
{
    // sounds like a matrix subtraction
    // no, likely just a double loop
    // no negative values, we use absolute as always

    $comVal = 0;
    foreach ($a as $ai) {
        $hasLessD = false;
        foreach ($b as $bi) {
            if (abs($ai - $bi) <= $d) {
                $hasLessD = true;
            }
        }

        if ($hasLessD) {
            $comVal++;
            $hasLessD = false;
        }
    }

    return $comVal;
}

print(comparatorValue([3, 1, 5], [5, 6, 7], 2));
print("\n");

print(comparatorValue([7, 5, 9], [13, 1, 4], 3));
print("\n");
print(comparatorValue([4, 12, 10, 13, 0], [9, 10, 4], 0));
print("\n");
// assert(1 == countingValleys(8, "UDDDUDUU"));
