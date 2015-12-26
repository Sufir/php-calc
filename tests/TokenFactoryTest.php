<?php
/**
 * TokenFactoryTest.php
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @date 26.12.2015 13:38:03
 */

namespace Sufir\Calc\Test;

use Sufir\Calc\Token\TokenFactory;
use Sufir\Calc\Token;

class TokenFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TokenFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new TokenFactory();
    }

    public function testParsingPhpExpression()
    {
        $bracket = $this->factory->bracket('(');
        $this->assertInstanceOf(Token\BracketToken::class, $bracket);

        $delimiter = $this->factory->delimiter(',');
        $this->assertInstanceOf(Token\DelimiterToken::class, $delimiter);

        $func = $this->factory->func('some_function');
        $this->assertInstanceOf(Token\FunctionToken::class, $func);

        $number = $this->factory->number(69);
        $this->assertInstanceOf(Token\NumberToken::class, $number);

        $operator = $this->factory->operator('+');
        $this->assertInstanceOf(Token\OperatorToken::class, $operator);

        $variable = $this->factory->variable('$var');
        $this->assertInstanceOf(Token\VariableToken::class, $variable);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExprObject()
    {
        $this->factory->create('unknown', 'expr');
    }
}
