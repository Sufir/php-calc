<?php

mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';

echo '<pre>';

$expr = "round(1/2+(2+3)/(sin(9-2)^2-6/7))*-1";

$parser = new \sufir\Calc\Lexer();
$tokens = $parser->parse($expr);

echo "\n", implode(' ', $tokens);

$converter = new sufir\Calc\Converter;
$tokensPRN = $converter->converToPostfix($tokens);

//echo "\n", $result = implode(' ', $tokensPRN);

$calc = new sufir\Calc\Calc;

$calc->registerFunction('sin', function($arg) {
    return sin($arg);
})->registerFunction('round', function($arg) {
    return round($arg, 2);
});

echo "\t= ", $calc->evaluate($tokensPRN);