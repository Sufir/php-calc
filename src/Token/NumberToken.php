<?php

/**
 * NumberToken.php
 *
 * @date 28.03.2015 2:49:03
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc\Token;

/**
 * NumberToken
 *
 * Число
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc\Token
 */
final class NumberToken extends AbstractToken
{
    /**
     *
     * @param string $value
     * @return integer|float
     */
    protected function sanitize($value)
    {
        return $value - 0;
    }

    /**
     *
     * @param string $value
     * @return boolean
     */
    protected function validate($value)
    {
        return is_numeric($value);
    }
}
