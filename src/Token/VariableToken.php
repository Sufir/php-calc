<?php

/**
 * VariableToken.php
 *
 * @date 28.03.2015 2:49:19
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc\Token;

/**
 * VariableToken
 *
 * Переменная
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc\Token
 */
final class VariableToken extends AbstractToken
{
    /**
     *
     * @param string $value
     * @return string
     */
    protected function sanitize($value)
    {
        return $value;
    }

    /**
     *
     * @param string $value
     * @return boolean
     */
    public static function validate($value)
    {
        return !!preg_match('/^[\$]{1}[[:alnum:]]+$/', $value);
    }
}
