<?php
/**
 * Token.php
 *
 * @date 26.12.2015 13:25:22
 */

namespace Sufir\Calc;

/**
 * Token
 *
 * Token interface.
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc
 */
interface Token
{
    const TYPE_FUNCTION = 'function',
        TYPE_OPERATOR = 'operator',
        TYPE_BRACKET = 'bracket',
        TYPE_DELIMITER = 'delimiter',
        TYPE_NUMBER = 'number',
        TYPE_VARIABLE = 'variable';

    /**
     *
     * @param string $value
     * @throws \InvalidArgumentException
     */
    public function __construct($value);

    /**
     *
     * @param string $value
     * @return boolean
     */
    public static function validate($value);

    /**
     *
     * @return string
     */
    public function getValue();

    /**
     *
     * @return boolean
     */
    public function isNumber();

    /**
     *
     * @return boolean
     */
    public function isFunction();

    /**
     *
     * @return boolean
     */
    public function isOperator();

    /**
     *
     * @return boolean
     */
    public function isBracket();

    /**
     *
     * @return boolean
     */
    public function isVariable();

    /**
     *
     * @return boolean
     */
    public function isDelimiter();
}
