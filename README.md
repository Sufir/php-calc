# sufir/php-calc

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Issues][ico-issues]][link-issues]
![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)

## Install

Via Composer

``` bash
$ composer require sufir/php-calc
```

## Usage

``` php
$lexer = new Lexer();
$converter = new Converter();
$calc = new Calc();

$calc->registerFunction('d20', function () {
    return rand(1, 20);
});

$charAbilities = ['ability' => 15, /*... etc */];
$difficultyClass = 12;
$checkExpr = 'd20() + ($ability / 2 – 5)';

$tokens = $lexer->parse($checkExpr);
$result = $calc->evaluate(
    $converter->converToPostfix($tokens),
    $charAbilities
);

// simple d20 ability check
if ($result < $difficultyClass) {
    echo 'You fail!!!';
} else {
    echo 'Congratulation!';
}
```

``` php
$lexer = new Lexer();
$converter = new Converter();
$calc = new Calc(20);

$expr = '$Pi*$r^2';
$radiusList = [5, 10, 15, 25, 50];
$calc->defineVar('$Pi', '3.14159265358979323846');
$tokens = $lexer->parse($expr);

foreach ($radiusList as $radius) {
    echo 'Pi * ', $radius , '^2 = ',
    $calc->evaluate(
        $converter->converToPostfix($tokens),
        ['r' => $radius]
    ),
    "\n";
}
```

```
Pi * 5^2  = 78.53981633974483096150
Pi * 10^2 = 314.15926535897932384600
Pi * 15^2 = 706.85834705770347865350
Pi * 25^2 = 1963.49540849362077403750
Pi * 50^2 = 7853.98163397448309615000
```

## Testing

``` bash
$ composer test
```

## Credits

- [Sufir][link-author]

## License

The MIT License (MIT).

[ico-version]: https://img.shields.io/packagist/v/Sufir/php-calc.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-travis]: https://img.shields.io/travis/Sufir/php-calc/master.svg
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Sufir/php-calc.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Sufir/php-calc.svg
[ico-downloads]: https://img.shields.io/packagist/dt/Sufir/php-calc.svg
[ico-issues]: https://img.shields.io/github/issues/Sufir/php-calc.svg

[link-packagist]: https://packagist.org/packages/Sufir/php-calc
[link-travis]: https://travis-ci.org/Sufir/php-calc
[link-scrutinizer]: https://scrutinizer-ci.com/g/Sufir/php-calc/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Sufir/php-calc
[link-downloads]: https://packagist.org/packages/Sufir/php-calc
[link-issues]: https://github.com/Sufir/php-calc/issues
[link-author]: https://github.com/Sufir