<?php

/**
 * VariableToken.php
 *
 * @date 28.03.2015 2:49:19
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace sufir\Calc\Token;

/**
 * VariableToken
 *
 * Переменная
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package sufir\Calc\Token
 */
class VariableToken extends AbstractToken
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
    protected function validate($value)
    {
        return preg_match('/[\$]?[a-zA-Z\_0-9]+/', $value);
    }
}
