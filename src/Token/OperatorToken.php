<?php

/**
 * OperatorToken.php
 *
 * @date 28.03.2015 2:48:12
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc\Token;

/**
 * OperatorToken
 *
 * Математический оператор
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc\Token
 */
final class OperatorToken extends AbstractToken
{
    /**
     *
     * @var array
     */
    protected static $operators = array('*', '/', '+', '-', '^');

    /**
     *
     * @param string $assoc
     * @return integer
     */
    public function getPriority($assoc = 'left')
    {
        if ($assoc === 'right') {
            switch ($this->value) {
                case '^':
                    return 5;
                case '/':
                    return 4;
                case '*':
                    return 3;
                case '-':
                    return 2;
                case '+':
                    return 1;
                default:
                    return 0;
            }
        } else {
            switch ($this->value) {
                case '^':
                    return 3;
                case '*':
                case '/':
                    return 2;
                case '+':
                case '-':
                    return 1;
                default:
                    return 0;
            }
        }
    }

    /**
     *
     * @return boolean
     */
    public function isLeftAssoc()
    {
        return !$this->isRightAssoc();
    }

    /**
     *
     * @return boolean
     */
    public function isRightAssoc()
    {
        return ($this->value === '^');
    }

    /**
     *
     * @return array
     */
    public static function getAllowedOperators()
    {
        return self::$operators;
    }

    /**
     *
     * @param string $value
     * @return string
     */
    protected function sanitize($value)
    {
        return trim($value);
    }

    /**
     *
     * @param string $value
     * @return boolean
     */
    public static function validate($value)
    {
        return in_array($value, self::getAllowedOperators());
    }
}
