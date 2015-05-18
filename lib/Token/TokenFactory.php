<?php

/**
 * TokenFactory.php
 *
 * @date 28.03.2015 3:37:00
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace sufir\Calc\Token;

/**
 * TokenFactory
 *
 * Description of TokenFactory
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package sufir\Calc\Token
 */
class TokenFactory
{
    protected $allowedTypes = array(
        IToken::TYPE_FUNCTION => true,
        IToken::TYPE_OPERATOR => true,
        IToken::TYPE_BRACKET => true,
        IToken::TYPE_DELIMITER => true,
        IToken::TYPE_NUMBER => true,
        IToken::TYPE_VARIABLE => true,
    );

    /**
     *
     * @param string $type
     * @param string $value
     * @return \sufir\Calc\Token\IToken
     * @throws \InvalidArgumentException
     */
    public function create($type, $value)
    {
        if (!$this->typeAllowed($type)) {
            throw new \InvalidArgumentException('Неизвестный тип токена!');
        }

        $className = 'sufir\Calc\Token\\' . self::camelize($type) . 'Token';

        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Неизвестный тип токена! Класс {$className} не найден.");
        }

        return $token = new $className($value);
    }

    /**
     *
     * @param string $value
     * @return \sufir\Calc\Token\OperatorToken
     */
    public function operator($value)
    {
        return $this->create(IToken::TYPE_OPERATOR, $value);
    }

    /**
     *
     * @param string $value
     * @return \sufir\Calc\Token\BracketToken
     */
    public function bracket($value)
    {
        return $this->create(IToken::TYPE_BRACKET, $value);
    }

    /**
     *
     * @param string $value
     * @return \sufir\Calc\Token\FunctionToken
     */
    public function func($value)
    {
        return $this->create(IToken::TYPE_FUNCTION, $value);
    }

    /**
     *
     * @param string $value
     * @return \sufir\Calc\Token\NumberToken
     */
    public function number($value)
    {
        return $this->create(IToken::TYPE_NUMBER, $value);
    }

    /**
     *
     * @param string $value
     * @return \sufir\Calc\Token\VariableToken
     */
    public function variable($value)
    {
        return $this->create(IToken::TYPE_VARIABLE, $value);
    }

    /**
     *
     * @param string $type
     * @return boolean
     */
    protected function typeAllowed($type)
    {
        return isset($this->allowedTypes[$type]);
    }

    /**
     *
     * @author Sklyarov Alexey <sufir@lightsoft.ru>
     * @param string $var
     * @return string
     */
    protected static function camelize($var)
    {
        return str_replace(' ', '', ucwords(str_replace(array('_', '-'), ' ', $var)));
    }
}
