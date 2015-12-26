<?php

/**
 * BracketToken.php
 *
 * @date 28.03.2015 2:48:37
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc\Token;

/**
 * BracketToken
 *
 * Скобки
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc\Token
 */
final class BracketToken extends AbstractToken
{
    const OPEN = '(';
    const CLOSE = ')';

    /**
     *
     * @var array
     */
    protected $allowedBrackets = array(
        BracketToken::OPEN => true,
        BracketToken::CLOSE => true
    );

    /**
     * Открывающая скобка?
     *
     * @return boolean
     */
    public function isOpen()
    {
        return ($this->value === BracketToken::OPEN);
    }

    /**
     * Закрывающая скобка?
     *
     * @return boolean
     */
    public function isClose()
    {
        return ($this->value === BracketToken::CLOSE);
    }

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
        return isset($this->allowedBrackets[$value]);
    }
}
