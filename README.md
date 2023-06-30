# cli-signal-handler
Sets or removes CTRL event handlers, which allows CLI processes to intercept or ignore CTRL+C and CTRL+BREAK events.

## Installation

Install the package via composer

```bash
composer require mageok/cli-signal-handler
```

## Documentation

```php
use Mageok\CliSignalHandler\CliCtrlScheduler;

CliCtrlScheduler::registerCtrlCEvent($handler1);
CliCtrlScheduler::registerCtrlCEvent($handler2);

```