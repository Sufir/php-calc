<?php

/**
 * DelimiterToken.php
 *
 * @date 04.04.2015 0:43:12
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Calc\Token;

/**
 * DelimiterToken
 *
 * Description of DelimiterToken
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Calc\Token
 */
final class DelimiterToken extends AbstractToken
{
    /**
     *
     * @param string $value
     * @return integer|float
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
        return ($value === ',');
    }
}
