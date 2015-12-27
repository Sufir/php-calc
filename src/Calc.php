<?php

/**
 * Calc.php
 *
 * @date 28.03.2015 0:54:24
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc;

use Closure;
use InvalidArgumentException;
use ReflectionObject;
use RuntimeException;
use SplStack;
use Sufir\Calc\Token;
use Sufir\Calc\Token\FunctionToken;
use Sufir\Calc\Token\NumberToken;
use Sufir\Calc\Token\VariableToken;

/**
 * Calc
 *
 * Description of Calc
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc
 */
final class Calc
{
    private $functions = [];

    private $variables = [];

    /**
     * @param integer $scale
     */
    public function __construct($scale = 5)
    {
        bcscale($scale);
    }

    /**
     *
     * @param Token $tokens
     * @param array $variables
     * @return string
     * @throws RuntimeException
     */
    public function evaluate(array $tokens, array $variables = [])
    {
        $stack = new SplStack;

        $this->defineVars($variables);

        foreach ($tokens as $token) {
            if ($token->isNumber()) {
                $stack->push($token);

            } elseif ($token->isVariable()) {
                if (!isset($this->variables[$token->getValue()])) {
                    throw new RuntimeException(
                        "Undefined variable: «{$token}»"
                    );
                }

                $stack->push(new NumberToken($this->variables[$token->getValue()]));

            } elseif ($token->isOperator()) {
                if ($stack->count() > 1) {
                    $second = $stack->pop();
                    $first = $stack->pop();

                    $result = self::math($token->getValue(), $first->getValue(), $second->getValue());

                    $stack->push(new NumberToken($result));
                } else {
                    throw new RuntimeException(
                        "Not enough operands for a binary operator: «{$token}»"
                    );
                }

            } elseif ($token->isFunction()) {
                if (!isset($this->functions[$token->getValue()])) {
                    throw new RuntimeException("Undefined function: «{$token}()»");
                }

                $countOfArguments = $this->functions[$token->getValue()]['args'];

                if ($stack->count() < $countOfArguments) {
                    throw new RuntimeException(
                        "Wrong argenents for function «{$token}()»:"
                        . "defined {$stack->count()} expected {$countOfArguments}"
                    );
                }

                $arguments = [];
                for ($idx = 0; $idx < $countOfArguments; $idx++) {
                    $arguments[] = $stack->pop()->getValue();
                }
                $arguments = array_reverse($arguments);

                $result = call_user_func_array($this->functions[$token->getValue()]['func'], $arguments);

                if (!is_numeric($result)) {
                    throw new RuntimeException(
                        "Wrong result type of «{$token->getValue()}()» function, expected integer or float"
                    );
                }

                $stack->push(new NumberToken($result));
            }
        }

        return $stack->top()->getValue();
    }

    /**
     * Register new function.
     *
     * @param string $name
     * @param Closure $callable
     * @return \Sufir\Calc\Calc
     */
    public function registerFunction($name, Closure $callable)
    {
        $name = rtrim($name, " \t\n\r\0\x0B\(\)");
        if (!FunctionToken::validate($name)) {
            throw new InvalidArgumentException("Wrong function name «{$name}»");
        }

        $objReflector = new ReflectionObject($callable);
        $reflector = $objReflector->getMethod('__invoke');
        $parameters = $reflector->getParameters();

        $this->functions[$name] = array(
            'args' => count($parameters),
            'func' => $callable,
        );

        return $this;
    }

    /**
     * Define new variable.
     *
     * @param string $name
     * @param integer $value
     * @return \Sufir\Calc\Calc
     * @throws RuntimeException
     */
    public function defineVar($name, $value)
    {
        $name = '$' . ltrim($name, " \t\n\r\0\x0B\$");
        if (!VariableToken::validate($name)) {
            throw new InvalidArgumentException("Wrong variable name «{$name}»");
        }

        if (!is_numeric($value)) {
            throw new InvalidArgumentException(
                "Wrong type of «{$name}», expected integer or float"
            );
        }

        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * Define new variables.
     * <br>
     * Array format ['varName' => 'varValue', ...]
     *
     * @param array $variables
     * @return \Sufir\Calc\Calc
     */
    public function defineVars(array $variables = [])
    {
        foreach ($variables as $name => $value) {
            $this->defineVar($name, $value);
        }

        return $this;
    }

    /**
     * @param string $operator
     * @param integer|float $firstOperand
     * @param integer|float $secondOperand
     * @return string|null
     */
    private static function math($operator, $firstOperand, $secondOperand)
    {
        switch ($operator) {
            case '^':
                return bcpow($firstOperand, $secondOperand);
            case '*':
                return bcmul($firstOperand, $secondOperand);
            case '/':
                return bcdiv($firstOperand, $secondOperand);
            case '+':
                return bcadd($firstOperand, $secondOperand);
            case '-':
                return bcsub($firstOperand, $secondOperand);
        }
    }
}
