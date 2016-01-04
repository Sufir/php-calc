<?php
/**
 * CalcTest.php
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @date 27.12.2015 10:07:05
 */

namespace Sufir\Calc\Test;

use Sufir\Calc\Calc;
use Sufir\Calc\Lexer;
use Sufir\Calc\Converter;

class CalcTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Calc
     */
    private $calc;
    /**
     * @var Lexer
     */
    private $lexer;
    /**
     * @var Converter
     */
    private $converter;

    protected function setUp()
    {
        $this->calc = new Calc(5);
        $this->lexer = new Lexer();
        $this->converter = new Converter();
    }

    /**
     * @param string $expr
     * @dataProvider getExpressions
     */
    public function testEvaluate(
        $expr,
        array $functions,
        array $variables,
        $expectedResult
    ) {
        $tokens = $this->lexer->parse($expr);
        $converted = $this->converter->converToPostfix($tokens);

        foreach ($functions as $functionName => $functionBody) {
            $this->calc->registerFunction($functionName, $functionBody);
        }

        $this->calc->defineVars($variables);

        $result = $this->calc->evaluate($converted);

        if (is_array($expectedResult)) {
            $this->assertGreaterThanOrEqual($expectedResult['min'], $result);
            $this->assertLessThanOrEqual($expectedResult['max'], $result);
        } else {
            $this->assertEquals($expectedResult, $result);
        }
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testWrongExpr()
    {
        $expr = '2 - 2 + 7 +';

        $tokens = $this->lexer->parse($expr);
        $converted = $this->converter->converToPostfix($tokens);

        $this->calc->evaluate($converted);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongVarName()
    {
        $this->calc->defineVar('wrong_var', 0);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongVarValue()
    {
        $this->calc->defineVar('myVar', 'qwerty');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testVarUndefined()
    {
        $expr = '2 * 2 + $undefinedVar';

        $tokens = $this->lexer->parse($expr);
        $converted = $this->converter->converToPostfix($tokens);

        $this->calc->evaluate($converted);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongFunctionRegister()
    {
        $this->calc->registerFunction('1wrong', function() {
            return 0;
        });
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testWrongFunctionResult()
    {
        $expr = '2 * 2 + get_wrong_result()';

        $this->calc->registerFunction('get_wrong_result', function() {
            return 'qwerty';
        });

        $tokens = $this->lexer->parse($expr);
        $converted = $this->converter->converToPostfix($tokens);

        $this->calc->evaluate($converted);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testWrongFunctionArguments()
    {
        $expr = 'wrong_agrs(2, 2)';

        $this->calc->registerFunction('wrong_agrs', function($arg1, $arg2, $arg3) {
            return $arg1 + $arg2 + $arg3;
        });

        $tokens = $this->lexer->parse($expr);
        $converted = $this->converter->converToPostfix($tokens);

        $this->calc->evaluate($converted);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testFunctionUndefined()
    {
        $expr = '2 * 2 + undefined_func()';

        $tokens = $this->lexer->parse($expr);
        $converted = $this->converter->converToPostfix($tokens);

        $this->calc->evaluate($converted);
    }

    /**
     *
     * @return string[][]
     */
    public function getExpressions()
    {
        return [
            [
                '2 * 2',
                [],
                [],
                '4'
            ],
            [
                '(1 + 2) * 4 + 3',
                [],
                [],
                '15'
            ],
            [
                 '0.1 * round(100.1, 0)',
                [
                    'round' => function ($val, $precision) {
                        return round($val, $precision);
                    }
                ],
                [],
                '10'
            ],
            [
                'round(1/2 + (2+3) / (sin(9-2)^2 - 6/7)) * -1',
                [
                    'round' => function ($val) {
                        return round($val, 2);
                    },
                    'sin' => function ($val) {
                        return sin($val);
                    }
                ],
                [],
                '11.25'
            ],
            [
                '-1 + round(1/2 + (2+3) / (sin(9-2)^2 - 6/7))',
                [
                    'round' => function ($val) {
                        return round($val, 2);
                    },
                    'sin' => function ($val) {
                        return sin($val);
                    }
                ],
                [],
                '-12.25'
            ],
            [
                'round(256.8799, 2)',
                [
                    'round' => function ($val, $precision) {
                        return round($val, $precision);
                    }
                ],
                [],
                '256.88'
            ],
            [
                '$Pi*$r^2',
                [],
                ['$Pi' => '3.1415926535897932384626433832795', '$r' => 10],
                '314.15926'
            ],
            [
                '$m*c()^2',
                [
                    'c' => function () {
                        return bcmul(3, bcpow(10, 8));
                    }
                ],
                [
                    '$m' => 0.1
                ],
                '9000000000000000.0'
            ],
            [
                'normal_distribution(1, $s, $m)',
                [
                    'normal_distribution' => function ($x, $σ = 1, $μ = 0) {
                        $Pi = '3.1415926536';
                        $p = bcmul(
                            bcdiv(
                                1,
                                bcmul($σ, pow(bcmul(2, $Pi), 0.5))
                            ),
                            exp(
                                -bcdiv(
                                    pow(bcsub($x, $μ), 2),
                                    bcmul(2, pow($σ, 2))
                                )
                            )
                        );

                        return $p;
                    }
                ],
                [
                    '$s' => 1,
                    '$m' => 0,
                ],
                '0.24196',
            ],
            [
                'd20()+$strength+$perception/2-$poison',
                [
                    'd20' => function () {
                        return rand(1, 20);
                    }
                ],
                [
                    '$strength' => 10,
                    '$perception' => 12,
                    '$poison' => 4,
                ],
                [
                    'min' => 13,
                    'max' => 32,
                ]
            ],
        ];
    }
}
