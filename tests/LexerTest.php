<?php
/**
 * LexerTest.php
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @date 26.12.2015 12:47:49
 */

namespace Sufir\Calc\Test;

use stdClass;
use Sufir\Calc\Lexer;

class LexerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Lexer
     */
    private $lexer;

    protected function setUp()
    {
        $this->lexer = new Lexer();
    }

    /**
     * @param string $expr
     * @dataProvider getPhpExpressions
     */
    public function testParsingPhpExpression($expr)
    {
        $tokens = $this->lexer->parse($expr);

        $resultExpr = implode('', $tokens);

        $this->assertEquals(eval("return {$expr};"), eval("return {$resultExpr};"));
    }

    /**
     * @param string $expr
     * @dataProvider getExtendedExpressions
     */
    public function testExtendedExpression($expr)
    {
        $tokens = $this->lexer->parse($expr);

        $resultExpr = implode('', $tokens);

        $this->assertEquals($expr, $resultExpr);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExprObject()
    {
        $this->lexer->parse(new stdClass());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExprArray()
    {
        $this->lexer->parse([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExprEmpty()
    {
        $this->lexer->parse(' ');
    }

    /**
     * @return string[][]
     */
    public function getPhpExpressions()
    {
        return [
            ['2*2'],
            ['round(1/2 + (2+3) / (sin(9-2)^2 - 6/7)) * -1'],
            ['-1 + round(1/2 + (2+3) / (sin(9-2)^2 - 6/7))'],
            ['round(256.879, 1)'],
        ];
    }

    /**
     * @return string[][]
     */
    public function getExtendedExpressions()
    {
        return [
            ['$Pi*$r^2'], // https://en.wikipedia.org/wiki/Area#List_of_formulas
            ['$m*$c^2'], // https://en.wikipedia.org/wiki/Mass–energy_equivalence
            ['d20()+$strength+$perception/2-$poison'], // https://en.wikipedia.org/wiki/D20_System
        ];
    }
}
