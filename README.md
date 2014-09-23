# Teacup Log Component

Small log file writer with adjustable log level settings.

## Usage

### Creating a logger

```php
$logger = new \Teacup\Log::create('/tmp', 'dummy.log');

//Just log notices and above.
$errorLog = new \Teacup\Log::create('./logs', 'error.log', \Teacup\Log::NOTICE);
```

### Log a message

\Teacup\Log provides methods for the log levels defined by RFC 5424 (debug, info, notice, warning, error, critical, alert and emergency). There's a method for each of these levels:

```php
$logger->emergency('This is an alert message');
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
$logger->log('this is a debug message', \Teacup\Log::DEBUG);
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

### Change the logging level of an existing instance

To change the logging level of an existing instance, use the `setLevel` method:

```php
$logger->setLevel(\Teacup\Log::DEBUG);
```

## Errors

`Log::__construct()` throws a `RuntimeException` if the file handle couldn't be opened.
`Log::log()` throws an `OutOfBoundsException` if the given log level isn't defined.