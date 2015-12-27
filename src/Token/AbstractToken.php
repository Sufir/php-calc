<?php

/**
 * AbstractToken.php
 *
 * @date 28.03.2015 2:49:42
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc\Token;

use InvalidArgumentException;
use Sufir\Calc\Token;

/**
 * AbstractToken
 *
 * Description of AbstractToken
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc\Token
 */
abstract class AbstractToken implements Token
{
    /**
     *
     * @var mixed
     */
    protected $value;

    /**
     *
     * @param string $value
     * @return \Sufir\Calc\Token\AbstractToken
     */
    public function __construct($value)
    {
        if (!static::validate($value)) {
            throw new InvalidArgumentException(
                "Wrong value «{$value}» for " . __CLASS__
            );
        }

        $this->value = $this->sanitize($value);

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *
     * @return boolean
     */
    public function isNumber()
    {
        return ($this instanceof NumberToken);
    }

    /**
     *
     * @return boolean
     */
    public function isFunction()
    {
        return ($this instanceof FunctionToken);
    }

    /**
     *
     * @return boolean
     */
    public function isOperator()
    {
        return ($this instanceof OperatorToken);
    }

    /**
     *
     * @return boolean
     */
    public function isBracket()
    {
        return ($this instanceof BracketToken);
    }

    /**
     *
     * @return boolean
     */
    public function isVariable()
    {
        return ($this instanceof VariableToken);
    }

    /**
     *
     * @return boolean
     */
    public function isDelimiter()
    {
        return ($this instanceof DelimiterToken);
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->getValue());
    }

    /**
     *
     * @param string $value
     * @return mixed
     */
    abstract protected function sanitize($value);
}
