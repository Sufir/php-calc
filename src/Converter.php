<?php

/**
 * Converter.php
 *
 * @date 29.03.2015 0:22:18
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc;

use SplStack;

/**
 * Converter
 *
 * Description of Converter
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc
 */
final class Converter
{
    /**
     *
     * @param AbstractToken[] $tokens
     */
    public function converToPostfix(array $tokens)
    {
        $output = array();
        $stack = new SplStack;

        // Пока есть ещё токены для чтения - читаем очередной токен
        foreach ($tokens as $token) {
            // Если токен является числом, добавляем его к выходной строке
            if ($token->isNumber()) {
                $output[] = $token;
                // Если токен является функцией, помещаем его в стек
            } elseif ($token->isFunction()) {
                $stack->push($token);
                // Если токен является открывающей скобкой, помещаем его в стек
            } elseif ($token->isBracket() && $token->isOpen()) {
                $stack->push($token);
                // Если токен - разделитель аргументов функции
            } elseif ($token->isDelimiter()) {
                // До тех пор, пока верхним элементом стека не станет открывающая скобка,
                // выталкиваем элементы из стека в выход
                while (!$stack->top()->isBracket()) {
                    $output[] = $stack->pop();
                }

                // Если токен является закрывающей скобкой
            } elseif ($token->isBracket() && $token->isClose()) {
                // До тех пор, пока верхним элементом стека не станет открывающая скобка,
                // выталкиваем элементы из стека в выход
                while (!$stack->top()->isBracket()) {
                    $output[] = $stack->pop();
                }

                // При этом открывающая скобка удаляется из стека, но в выходную строку не добавляется
                $stack->pop();

                // Если после этого шага на вершине стека оказывается токен функции, выталкиваем его в выход
                if (!$stack->isEmpty() && $stack->top()->isFunction()) {
                    $output[] = $stack->pop();
                }

            // Если токен - оператор, выталкиваем верхние элементы стека в выход,
            // пока верхним элементом является оператор
            } elseif ($token->isOperator()) {
                /* @var $token \Sufir\Calc\Token\OperatorToken */
                while (!$stack->isEmpty() &&
                    $stack->top()->isOperator() &&
                    (($token->isLeftAssoc() && $token->getPriority() <= $stack->top()->getPriority()) ||
                    ($token->isRightAssoc() && $token->getPriority() < $stack->top()->getPriority()))
                ) {
                    $output[] = $stack->pop();
                }

                // помещаем оператор в стек
                $stack->push($token);
            }
        }

        // выталкиваем все оставшиеся элементы из стека в выход
        while (!$stack->isEmpty()) {
            $output[] = $stack->pop();
        }

        return $output;
    }
}
