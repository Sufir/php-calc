<?php

/**
 * Converter.php
 *
 * @date 29.03.2015 0:22:18
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc;

use SplStack;
use Sufir\Calc\Token\AbstractToken;

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
     * @param AbstractToken[] $tokens
     * @return AbstractToken[]
     */
    public function converToPostfix(array $tokens)
    {
        $output = array();
        $stack = new SplStack;

        foreach ($tokens as $token) {
            if ($token->isNumber()) {
                $output[] = $token;

            } elseif ($token->isFunction()) {
                $stack->push($token);

            } elseif ($token->isBracket() && $token->isOpen()) {
                $stack->push($token);

            } elseif ($token->isDelimiter()) {

                while (!$stack->top()->isBracket()) {
                    $output[] = $stack->pop();
                }

            } elseif ($token->isBracket() && $token->isClose()) {

                while (!$stack->top()->isBracket()) {
                    $output[] = $stack->pop();
                }

                $stack->pop();

                if (!$stack->isEmpty() && $stack->top()->isFunction()) {
                    $output[] = $stack->pop();
                }

            } elseif ($token->isOperator()) {
                /* @var $token \Sufir\Calc\Token\OperatorToken */
                while (!$stack->isEmpty() &&
                    $stack->top()->isOperator() &&
                    (($token->isLeftAssoc() && $token->getPriority() <= $stack->top()->getPriority()) ||
                    ($token->isRightAssoc() && $token->getPriority() < $stack->top()->getPriority()))
                ) {
                    $output[] = $stack->pop();
                }

                $stack->push($token);
            }
        }

        while (!$stack->isEmpty()) {
            $output[] = $stack->pop();
        }

        return $output;
    }
}
