<?php
/**
 * TokenTest.php
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @date 26.12.2015 13:53:42
 */

namespace Sufir\Calc\Test;

use Sufir\Calc\Token\TokenFactory;
use Sufir\Calc\Token;

class TokenTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    public function testBracketToken()
    {
        $bracketOpen = new Token\BracketToken('(');
        $this->assertInstanceOf(Token\BracketToken::class, $bracketOpen);
        $this->assertInternalType('boolean', $bracketOpen->isOpen());
        $this->assertTrue($bracketOpen->isOpen());

        $bracketClose = new Token\BracketToken(')');
        $this->assertInstanceOf(Token\BracketToken::class, $bracketClose);
        $this->assertInternalType('boolean', $bracketClose->isClose());
        $this->assertTrue($bracketClose->isClose());

        $this->assertNotEquals($bracketOpen->isClose(), $bracketClose->isClose());
        $this->assertNotEquals($bracketOpen->isOpen(), $bracketClose->isOpen());
    }
    /**
     * @param string $expr
     * @dataProvider getNotBrackets
     * @expectedException \InvalidArgumentException
     */
    public function testIsBracketInvalid($expr)
    {
        new Token\BracketToken($expr);
    }

    /**
     * @return \Sufir\Calc\Token\DelimiterToken
     */
    public function testDelimiterToken()
    {
        $delimiter = new Token\DelimiterToken(',');
        $this->assertInstanceOf(Token\DelimiterToken::class, $delimiter);
        return $delimiter;
    }
    /**
     * @param Token $token
     * @depends testDelimiterToken
     */
    public function testIsDelimiter(Token\DelimiterToken $token)
    {
        $this->assertTrue($token->isDelimiter());

        $this->assertFalse($token->isNumber());
        $this->assertFalse($token->isBracket());
        $this->assertFalse($token->isFunction());
        $this->assertFalse($token->isOperator());
        $this->assertFalse($token->isVariable());
    }
    /**
     * @param string $expr
     * @dataProvider getNotDelimiters
     * @expectedException \InvalidArgumentException
     */
    public function testIsDelimiterInvalid($expr)
    {
        new Token\DelimiterToken($expr);
    }

    /**
     * @return \Sufir\Calc\Token\FunctionToken
     */
    public function testFunctionToken()
    {
        $func = new Token\FunctionToken('some_function');
        $this->assertInstanceOf(Token\FunctionToken::class, $func);
        return $func;
    }
    /**
     * @param Token $token
     * @depends testFunctionToken
     */
    public function testIsFunc(Token\FunctionToken $token)
    {
        $this->assertTrue($token->isFunction());

        $this->assertFalse($token->isNumber());
        $this->assertFalse($token->isBracket());
        $this->assertFalse($token->isDelimiter());
        $this->assertFalse($token->isOperator());
        $this->assertFalse($token->isVariable());
    }
    /**
     * @param string $expr
     * @dataProvider getNotFunctions
     * @expectedException \InvalidArgumentException
     */
    public function testIsFunctionInvalid($expr)
    {
        new Token\FunctionToken($expr);
    }

    /**
     * @return \Sufir\Calc\Token\NumberToken
     */
    public function testNumberToken()
    {
        $number = new Token\NumberToken(69);
        $this->assertInstanceOf(Token\NumberToken::class, $number);
        return $number;
    }
    /**
     * @param Token $token
     * @depends testNumberToken
     */
    public function testIsNumber(Token\NumberToken $token)
    {
        $this->assertTrue($token->isNumber());

        $this->assertFalse($token->isFunction());
        $this->assertFalse($token->isBracket());
        $this->assertFalse($token->isDelimiter());
        $this->assertFalse($token->isOperator());
        $this->assertFalse($token->isVariable());
    }

    /**
     * @return \Sufir\Calc\Token\VariableToken
     */
    public function testVariableToken()
    {
        $variable = new Token\VariableToken('$var');
        $this->assertInstanceOf(Token\VariableToken::class, $variable);
        return $variable;
    }
    /**
     * @param Token $token
     * @depends testVariableToken
     */
    public function testIsVariable(Token\VariableToken $token)
    {
        $this->assertTrue($token->isVariable());

        $this->assertFalse($token->isNumber());
        $this->assertFalse($token->isBracket());
        $this->assertFalse($token->isDelimiter());
        $this->assertFalse($token->isOperator());
        $this->assertFalse($token->isFunction());
    }
    /**
     * @param string $expr
     * @dataProvider getNotVariables
     * @expectedException \InvalidArgumentException
     */
    public function testIsVariableInvalid($expr)
    {
        new Token\VariableToken($expr);
    }

    /**
     * @param string $expr
     * @dataProvider getOperators
     */
    public function testOperatorToken($expr)
    {
        $operator = new Token\OperatorToken($expr);
        $this->assertContains($operator->getValue(), Token\OperatorToken::getAllowedOperators());
        $this->assertInstanceOf(Token\OperatorToken::class, $operator);
        $this->assertInternalType('boolean', $operator->isRightAssoc());
        $this->assertInternalType('boolean', $operator->isLeftAssoc());
        $this->assertNotEquals($operator->isLeftAssoc(), $operator->isRightAssoc());
        $this->assertGreaterThan(0, $operator->getPriority('left'));
        $this->assertGreaterThan(0, $operator->getPriority('right'));

        return $operator;
    }
    /**
     * @param string $expr
     * @dataProvider getOperators
     */
    public function testIsOperator($expr)
    {
        $token = new Token\OperatorToken($expr);
        $this->assertTrue($token->isOperator());

        $this->assertFalse($token->isNumber());
        $this->assertFalse($token->isBracket());
        $this->assertFalse($token->isDelimiter());
        $this->assertFalse($token->isFunction());
        $this->assertFalse($token->isVariable());
    }
    /**
     * @param string $expr
     * @dataProvider getNotOperators
     * @expectedException \InvalidArgumentException
     */
    public function testIsOperatorInvalid($expr)
    {
        new Token\OperatorToken($expr);
    }

    /**
     * @return string[][]
     */
    public function getOperators()
    {
        return [
            ['+'],
            ['-'],
            ['/'],
            ['*'],
            ['^'],
        ];
    }

    /**
     * @return string[][]
     */
    public function getNotOperators()
    {
        return [
            ['$var'],
            [','],
            [';'],
            ['unknown'],
            ['('],
            [')'],
            [3.14],
        ];
    }

    /**
     * @return string[][]
     */
    public function getNotVariables()
    {
        return [
            [','],
            [';'],
            ['unknown'],
            ['$unknown.var'],
            [3.14],
            ['('],
            [')'],
            ['+'],
            ['-'],
            ['/'],
            ['*'],
            ['^'],
        ];
    }

    /**
     * @return string[][]
     */
    public function getNotFunctions()
    {
        return [
            [','],
            [';'],
            ['$unknown'],
            ['unknown.function'],
            ['123unknown.function'],
            ['('],
            [')'],
            ['+'],
            ['-'],
            ['/'],
            ['*'],
            ['^'],
            [3.14],
        ];
    }

    /**
     * @return string[][]
     */
    public function getNotDelimiters()
    {
        return [
            [';'],
            ['$unknown'],
            ['unknown.function'],
            ['123unknown.function'],
            ['unknown'],
            ['('],
            [')'],
            ['+'],
            ['-'],
            ['/'],
            ['*'],
            ['^'],
            [3.14],
        ];
    }

    /**
     * @return string[][]
     */
    public function getNotBrackets()
    {
        return [
            [','],
            [';'],
            ['$unknown'],
            ['unknown.function'],
            ['123unknown.function'],
            ['unknown'],
            ['['],
            [']'],
            ['+'],
            ['-'],
            ['/'],
            ['*'],
            ['^'],
            [3.14],
        ];
    }
}
