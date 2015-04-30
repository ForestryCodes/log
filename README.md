# Forestry Log

[![Latest Version](https://img.shields.io/github/release/ForestryCodes/log.svg?style=flat-square)](https://github.com/ForestryCodes/log/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/ForestryCodes/log/master.svg?style=flat-square)](https://travis-ci.org/ForestryCodes/log)
[![Codacy Badge](https://www.codacy.com/project/badge/9be442da34e34e548af5312f844fc0fe)](https://www.codacy.com/public/danielgithub/log)
[![Total Downloads](https://img.shields.io/packagist/dt/forestry/log.svg?style=flat-square)](https://packagist.org/packages/forestry/log)

Small log file writer with adjustable log level threshold settings.

## Install

Via Composer

``` bash
$ composer require forestry/log
```

## Usage

### Creating a logger

```php
$logger = new Forestry\Log\Log('/tmp/dummy.log');
//Just log notices and above.
$errorLog = new Forestry\Log\Log('./logs/error.log', Psr\Log\LogLevel::NOTICE);
```

#### Using a factory

Another way to create an instance is the use of one of the factories. There is one for each threshold level.

Here is an example for a logger with an error threshold:

```php
$factory = new ErrorLogger();
$logger = $factory->create('/tmp/error.log');
```

### Log a message

Forestry\Log provides methods for the log levels defined by RFC 5424 (debug, info, notice, warning, error, critical, alert and emergency). There's a method for each of these levels:

```php
$logger->emergency('This is an emergency message');
$logger->alert('This is an alert message');
$logger->critical('This is an critical message');
$logger->error('This is an error message');
$logger->warning('This is an warning message');
$logger->notice('This is a notice message');
$logger->info('This is an information');
$logger->debug('This is a debug message');
```

You can also use a generic `log` method:

```php
$logger->log(Psr\Log\LogLevel::DEBUG, 'this is a debug message');
```

#### Using placeholders in log messages

You can use placeholders in the your message string and fill them using the associative context array. The array keys have to match the placeholders without the curly brackets:

```php
$user = array('name' => 'John Doe', 'mail' => 'j.doe@example.org');
$logger->info('Send mail to {name} ({mail})', $user); //Send mail to John Doe (j.doe@example.org)
```

### Change the date format

The default date format is `Y-m-d H:i:s`. You can change it by using the `setDateFormat` method:

```php
$logger->setDateFormat('r'); //e.g. Thu, 21 Dec 2000 16:01:07 +0200
```

This method accepts any string which is compatible with PHPs native `date()`.

### Change the message format

The default format for the log message is `date level message`. To change it, you can re-arrange the placeholders with `setLogFormat`:

```php
$logger->setLogFormat('[{level}|{date}] {message}'); //[INFO|2013-04-25 13:37:42] This is an info message
```

There are the following placeholder available:

* {date}
* {level}
* {message}

These placeholders will be replaced with ones for `sprintf()`, so you can also use the following:

* `%1$s` = date
* `%2$s` = level
* `%3$s` = message

### Change the threshold level of an existing instance

To change the threshold level of an existing instance, use the `setLogThreshold` method:

```php
$logger->setLogThreshold(Psr\Log\LogLevel::DEBUG);
```

### Get current logging level

To get the current threshold level of an existing instance, use the `getLogThreshold` method:

```php
$level = $logger->getLogThreshold();
$logger->setLogThreshold(Psr\Log\LogLevel::INFO);
$logger->logInfo('my info');
$logger->setLogThreshold($level);
```

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [daniel-melzer](https://github.com/daniel-melzer)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
