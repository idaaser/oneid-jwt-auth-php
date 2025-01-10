<?php
declare(strict_types=1);

function check_invalid_string($param): bool{
    return $param == null || !is_string($param) || strlen(trim($param)) == 0;
}
