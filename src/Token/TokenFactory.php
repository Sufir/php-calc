<?php

/**
 * TokenFactory.php
 *
 * @date 28.03.2015 3:37:00
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc\Token;

use Sufir\Calc\Token;
use InvalidArgumentException;

/**
 * TokenFactory
 *
 * Tokens Factory
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc\Token
 */
final class TokenFactory
{
    private static $classes = array(
        Token::TYPE_FUNCTION => '\Sufir\Calc\Token\FunctionToken',
        Token::TYPE_OPERATOR => '\Sufir\Calc\Token\OperatorToken',
        Token::TYPE_BRACKET => '\Sufir\Calc\Token\BracketToken',
        Token::TYPE_DELIMITER => '\Sufir\Calc\Token\DelimiterToken',
        Token::TYPE_NUMBER => '\Sufir\Calc\Token\NumberToken',
        Token::TYPE_VARIABLE => '\Sufir\Calc\Token\VariableToken',
    );

    /**
     *
     * @param string $type
     * @param string $value
     * @return \Sufir\Calc\Token\Token
     * @throws InvalidArgumentException
     */
    public function create($type, $value)
    {
        $className = $this->getClassName($type);

        return new $className($value);
    }

    /**
     * @param string $value
     * @return \Sufir\Calc\Token\DelimiterToken
     */
    public function delimiter($value)
    {
        return $this->create(Token::TYPE_DELIMITER, $value);
    }

    /**
     *
     * @param string $value
     * @return \Sufir\Calc\Token\OperatorToken
     */
    public function operator($value)
    {
        return $this->create(Token::TYPE_OPERATOR, $value);
    }

    /**
     *
     * @param string $value
     * @return \Sufir\Calc\Token\BracketToken
     */
    public function bracket($value)
    {
        return $this->create(Token::TYPE_BRACKET, $value);
    }

    /**
     *
     * @param string $value
     * @return \Sufir\Calc\Token\FunctionToken
     */
    public function func($value)
    {
        return $this->create(Token::TYPE_FUNCTION, $value);
    }

    /**
     *
     * @param string $value
     * @return \Sufir\Calc\Token\NumberToken
     */
    public function number($value)
    {
        return $this->create(Token::TYPE_NUMBER, $value);
    }

    /**
     *
     * @param string $value
     * @return \Sufir\Calc\Token\VariableToken
     */
    public function variable($value)
    {
        return $this->create(Token::TYPE_VARIABLE, $value);
    }

    /**
     *
     * @param string $type
     * @return string
     * @throws InvalidArgumentException
     */
    private function getClassName($type)
    {
        if (!isset(self::$classes[$type])) {
            throw new InvalidArgumentException('Неизвестный тип токена!');
        }

        return self::$classes[$type];
    }
}
