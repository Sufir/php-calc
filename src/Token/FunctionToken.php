<?php

/**
 * FunctionToken.php
 *
 * @date 28.03.2015 2:47:36
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc\Token;

/**
 * FunctionToken
 *
 * Description of FunctionToken
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc\Token
 */
final class FunctionToken extends AbstractToken
{
    /**
     *
     * @param string $value
     * @return string
     */
    protected function sanitize($value)
    {
        return strtolower($value);
    }

    /**
     *
     * @param string $value
     * @return boolean
     */
    protected function validate($value)
    {
        return preg_match("/^[a-zA-Z]{1}[a-zA-Z0-9\_]+$/i", $value);
    }
}
