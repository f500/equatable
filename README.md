# F500 \ Equatable

[![Build Status](https://scrutinizer-ci.com/g/f500/equatable/badges/build.png?b=master)](https://scrutinizer-ci.com/g/f500/equatable/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/f500/equatable/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/f500/equatable/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/f500/equatable/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/f500/equatable/?branch=master)

Equatable objects and collections in PHP.

We reach a limitation in PHP when we want to compare objects in such a way that `==` yields incorrect results.
We often overcome this limitation by implementing a method like `equals($other)` encapsulating our specialized behaviour.
This library provides an interface for this.

We hit secondary problems when we try to work with these objects, especially when dealing with arrays of them.
Functions like `in_array()` and `array_search()` don't use our specialized `equals()` method, so they in turn yield incorrect results.
To overcome these problems, this library provides interfaces for a map (ordered dictionary-style collection) and a vector (stack-like collection),
as well as concrete immutable implementations designed to contain equatable objects.

Authored by [Jasper N. Brouwer][jaspernbrouwer].

Under the collective flag of [Future500 B.V.][f500]

## Installation

```txt
composer.phar require f500/equatable
```

## License

[The MIT License (MIT)][license]


[f500]: https://github.com/f500
[jaspernbrouwer]: https://github.com/jaspernbrouwer
[license]: https://github.com/f500/equatable/blob/master/LICENSE
