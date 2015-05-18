<?php

/**
 * IToken.php
 *
 * @date 28.03.2015 2:45:25
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace sufir\Calc\Token;

/**
 * IToken
 *
 * Description of IToken
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package sufir\Calc\Token
 */
interface IToken
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
     * @return \sufir\Calc\Token\IToken
     * @throws \InvalidArgumentException
     */
    public function __construct($value);

    /**
     *
     * @return string
     */
    public function getValue();
}
