<?php
function mesesEnEspanol(): array
{
    return [
        1  => 'enero',
        2  => 'febrero',
        3  => 'marzo',
        4  => 'abril',
        5  => 'mayo',
        6  => 'junio',
        7  => 'julio',
        8  => 'agosto',
        9  => 'septiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre',
    ];
}

function formatearTimestampLargo(int $ts): string
{
    $meses = mesesEnEspanol();
    return (int) date('j', $ts) . ' de ' . $meses[(int) date('n', $ts)] . ' de ' . date('Y', $ts);
}

function formatearTimestampLargoConHora(int $ts): string
{
    return formatearTimestampLargo($ts) . ' a las ' . date('H:i', $ts) . ' hs';
}

function formatearFechaLarga(string $fecha): string
{
    $ts = strtotime($fecha);
    return $ts === false ? '' : formatearTimestampLargo($ts);
}

function formatearFechaCorta(string $fecha): string
{
    $ts = strtotime($fecha);
    if ($ts === false) {
        return '';
    }
    $meses = mesesEnEspanol();
    $abreviatura = mb_substr($meses[(int) date('n', $ts)], 0, 3);
    return (int) date('j', $ts) . ' ' . $abreviatura . ' ' . date('Y', $ts);
}
