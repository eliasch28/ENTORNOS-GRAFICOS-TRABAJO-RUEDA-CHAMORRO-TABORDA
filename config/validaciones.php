<?php

function emailConDominioValido(string $email): bool
{
    $email = trim($email);
    if ($email === '') {
        return false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    return preg_match('/^[^@\s]+@[^@\s]+\.[^@\s]+$/', $email) === 1;
}
