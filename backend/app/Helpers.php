<?php

function cutPercentageSign(string $value): int {
    return intval(explode('%', $value)[0]);
}