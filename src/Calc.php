<?php

/**
 * Calc.php
 *
 * @date 28.03.2015 0:54:24
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc;

use Sufir\Calc\Token\AbstractToken;

/**
 * Calc
 *
 * Description of Calc
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc
 */
class Calc
{
    protected $functions = [];

    protected $variables = [];

    /**
     *
     * @param \Sufir\Calc\Token\AbstractToken[] $tokens
     */
    public function evaluate(array $tokens)
    {
        $stack = new \SplStack;
        foreach ($tokens as $token) {
            if ($token->isNumber()) {
                $stack->push($token);

            } elseif ($token->isOperator()) {
                if ($stack->count() < 2) {
                    throw new \Exception(
                        'Недостаточно операндов для бинарного оператора: '
                        . $token
                    );

                } elseif ($stack->count() > 1) {
                    $second = $stack->pop();
                    $first = $stack->pop();

                    $result = $this->calc((string) $token, (string) $first, (string) $second);

                    $stack->push($result);
                }

            } elseif ($token->isFunction()) {
                if (!isset($this->functions[$token->getValue()])) {
                    throw new \Exception('Не найдена функция: ' . $token->getValue());
                }

                $countOfArguments = $this->functions[$token->getValue()]['args'];

                if ($stack->count() < $countOfArguments) {
                    throw new \Exception(
                        'Недостаточно аргументов для функции: '
                        . $token
                    );
                }

                $arguments = array();

                for ($idx = 0; $idx < $countOfArguments; $idx++) {
                    $arguments[] = ((string) $stack->pop()) - 0;
                }

                $arguments = array_reverse($arguments);

                $result = call_user_func_array($this->functions[$token->getValue()]['func'], $arguments);

                $stack->push($result);
            }
        }

        return $stack->top();
    }

    /**
     *
     * @param string $name
     * @param \Closure $callable
     * @return \Sufir\Calc\Calc
     */
    public function registerFunction($name, \Closure $callable)
    {
        $objReflector = new \ReflectionObject($callable);
        $reflector = $objReflector->getMethod('__invoke');
        $parameters = $reflector->getParameters();

        $this->functions[$name] = array(
            'args' => count($parameters),
            'func' => $callable,
        );

        return $this;
    }

    /**
     * @todo
     * @param string $name
     * @return \Sufir\Calc\Calc
     */
    /*public function registerVariable($name, $value)
    {
        return $this;
    }*/

    /**
     * @param string $operator
     * @param integer|float $firstOperand
     * @param integer|float $secondOperand
     * @return int
     */
    protected function calc($operator, $firstOperand, $secondOperand)
    {
        switch ($operator) {
            case '^':
                return pow($firstOperand, $secondOperand);
            case '*':
                return $firstOperand * $secondOperand;
            case '/':
                return $firstOperand / $secondOperand;
            case '+':
                return $firstOperand + $secondOperand;
            case '-':
                return $firstOperand - $secondOperand;
            default:
                return 0;
        }
    }
}
