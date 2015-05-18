<?php

/**
 * ExpressionParser.php
 *
 * @date 28.03.2015 1:03:37
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace sufir\Calc;

use sufir\Calc\Token\IToken;
use sufir\Calc\Token\TokenFactory;

/**
 * ExpressionParser
 *
 * Description of ExpressionParser
 *
 * @todo Multibyte encoding support.
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package sufir\Calc
 */
class Lexer
{
    /**
     * Разбивает переданную строку на токены
     *
     * @param string $expr
     * @return IToken[]
     */
    public function parse($expr)
    {
        if (!is_string($expr) || strlen($expr) < 1) {
            throw new \InvalidArgumentException('Неверное выражение!');
        }

        $stack = array();
        $tokenFactory = new TokenFactory();
        $lastToken = '';
        $lastTokenType = null;

        for ($i = 0; $i < mb_strlen($expr); $i++) {
            $char = $expr[$i];

            // Скобки
            if ($char === '(' || $char === ')') {
                if ($lastTokenType) {
                    $stack[] = $tokenFactory->create($lastTokenType, $lastToken);
                }

                $lastTokenType = IToken::TYPE_BRACKET;
                $lastToken = $char;

            // Разделители параметров функции
            } elseif ($char === ',') {
                if ($lastTokenType) {
                    $stack[] = $tokenFactory->create($lastTokenType, $lastToken);
                }

                $lastTokenType = IToken::TYPE_DELIMITER;
                $lastToken = $char;

            } elseif ($char === '-') {
                // Если предыдущий токен не число и не закрывающая скобка, то минус означает отрицательное число
                if (!$lastTokenType) {
                    $lastTokenType = IToken::TYPE_NUMBER;
                } elseif ($lastTokenType === IToken::TYPE_NUMBER || $lastToken === ')') {
                    $stack[] = $tokenFactory->create($lastTokenType, $lastToken);
                    $lastTokenType = IToken::TYPE_OPERATOR;
                } else {
                    $stack[] = $tokenFactory->create($lastTokenType, $lastToken);
                    $lastTokenType = IToken::TYPE_NUMBER;
                }

                $lastToken = $char;

            } elseif (preg_match("/[\+\*\/\^]+/i", $char)) {
                if ($lastTokenType) {
                    $stack[] = $tokenFactory->create($lastTokenType, $lastToken);
                }

                $lastTokenType = IToken::TYPE_OPERATOR;
                $lastToken = $char;

                // Начало переменной
            } elseif ($char === '$') {
                if (!$lastTokenType) {
                    $lastTokenType = IToken::TYPE_VARIABLE;
                    $lastToken = $char;
                } else {
                    $stack[] = $tokenFactory->create($lastTokenType, $lastToken);
                    $lastTokenType = IToken::TYPE_VARIABLE;
                    $lastToken = $char;
                }

            // Буквы a-z, A-Z или символ подчеркивания (могут использоваться в именах переменных и функций)
            } elseif (preg_match("/[a-zA-Z\_]+/i", $char)) {
                if (!$lastTokenType) {
                    $lastTokenType = IToken::TYPE_FUNCTION;
                    $lastToken = $char;
                } elseif ($lastTokenType === IToken::TYPE_FUNCTION || $lastTokenType === IToken::TYPE_VARIABLE) {
                    $lastToken .= $char;
                } else {
                    $stack[] = $tokenFactory->create($lastTokenType, $lastToken);
                    $lastTokenType = IToken::TYPE_FUNCTION;
                    $lastToken = $char;
                }

            // Числа
            } elseif (is_numeric($char) || $char === '.') {
                if (!$lastTokenType) {
                    $lastTokenType = IToken::TYPE_NUMBER;
                    $lastToken = $char;
                } elseif ($lastTokenType === IToken::TYPE_NUMBER || $lastTokenType === IToken::TYPE_VARIABLE) {
                    $lastToken .= $char;
                } else {
                    $stack[] = $tokenFactory->create($lastTokenType, $lastToken);
                    $lastTokenType = IToken::TYPE_NUMBER;
                    $lastToken = $char;
                }
            }
        }

        $stack[] = $tokenFactory->create($lastTokenType, $lastToken);

        return $stack;
    }
}
