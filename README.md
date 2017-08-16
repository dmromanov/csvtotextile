# Csv To Textile

CsvToTextile is a tool for converting CSV-tables into Textile-formatted textual tables written in PHP.

[![Latest Stable Version](https://img.shields.io/packagist/v/dmromanov/csvtotextile.svg?style=flat)](https://packagist.org/packages/dmromanov/csvtotextile)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg?style=flat)](https://php.net/)
[![Build Status](https://travis-ci.org/dmromanov/csvtotextile.svg?branch=master)](https://travis-ci.org/dmromanov/csvtotextile)
[![codecov](https://codecov.io/gh/dmromanov/csvtotextile/branch/master/graph/badge.svg)](https://codecov.io/gh/dmromanov/csvtotextile)

## Usage

The program can be run either from sources or from a [PHP Archive (PHAR)](https://php.net/phar)

To run from a PHAR-file, download it on your computer and run the following command.
```bash
$ php csvtotextile.phar foo.csv --output foo.textile.txt
```

## Development

A PHAR-file can be build from sources with the following command:

```bash
$ php -d phar.readonly=0 vendor/bin/phing build-phar
```
