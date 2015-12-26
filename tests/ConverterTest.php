<?php
/**
 * ConverterTest.php
 *
 * @date 26.12.2015 19:20:53
 */

namespace Sufir\Calc\Test;

use Sufir\Calc\Converter;
use Sufir\Calc\Lexer;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Converter
     */
    private $сonverter;
    /**
     * @var Instantiator
     */
    private $lexer;

    protected function setUp()
    {
        $this->lexer = new Lexer();
        $this->сonverter = new Converter();
    }

    /**
     * @param string $expr
     * @dataProvider getPhpExpressions
     */
    public function test($expr, $canonical)
    {
        $tokens = $this->lexer->parse($expr);
        $converted = $this->сonverter->converToPostfix($tokens);

        //echo "\n\n-----------------\n", $expr, "\n", $resultExpr = implode(' ', $converted), "\n";
        //echo "\n\n-----------------\n", $expr, "\n", $canonical, "\n", implode(' ', $converted), "\n";

        $this->assertEquals($canonical, implode(' ', $converted));
    }

    /**
     *
     * @return string[][]
     */
    public function getPhpExpressions()
    {
        return [
            [
                '2 * 2',
                '2 2 *',
            ],
            [
                '0.1 * round(100.1, 0)',
                '0.1 100.1 0 round *',
            ],
            [
                'round(1/2 + (2+3) / (sin(9-2)^2 - 6/7)) * -1',
                '1 2 / 2 3 + 9 2 - sin 2 ^ 6 7 / - / + round -1 *',
            ],
            [
                '-1 + round(1/2 + (2+3) / (sin(9-2)^2 - 6/7))',
                '-1 1 2 / 2 3 + 9 2 - sin 2 ^ 6 7 / - / + round +',
            ],
            [
                'round(256.879, 2)',
                '256.879 2 round',
            ],
            /*[
                '$Pi*$r^2'
            ],
            [
                '$m*$c^2'
            ],
            [
                'd20()+$strength+$perception/2-$poison'
            ],*/
        ];
    }
}
