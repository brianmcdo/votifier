#Votifier Server
[![License](https://poser.pugx.org/bfoxwell/votifier/license.png)](https://packagist.org/packages/bfoxwell/votifier)
[![Latest Stable Version](https://poser.pugx.org/bfoxwell/votifier/v/stable.png)](https://packagist.org/packages/bfoxwell/votifier)
[![Total Downloads](https://poser.pugx.org/bfoxwell/votifier/downloads.png)](https://packagist.org/packages/bfoxwell/votifier)
[![Monthly Downloads](https://poser.pugx.org/bfoxwell/votifier/d/monthly.png)](https://packagist.org/packages/bfoxwell/votifier)

## Installation

Add `bfoxwell/votifier` to `composer.json`.

    "bfoxwell/votifier": "dev-master"

Run `composer update` to pull down the latest version of the Votifier Server.

## Usage

Generate Keys
```
openssl genrsa -out private.pem 2048
```

```
openssl rsa -in private.pem -pubout > public.pem
```

### Set the Configuration

```php
    $config = [
    	'key' => __DIR__ . '/private.pem', // Required | /path/to/private-key.pem
    	'passphrase' => '', // Optional
    	'address' => '0.0.0.0', // Optional | Default: localhost
    	'port' => 8192, // Optional | Default: 8192
    ];
```

### Initialize

```php
    // $message returns array | $logger returns instance of Psr\Log\LoggerInterface;
    $server = new Votifier($config, function($message, $logger)
    {
        // Your code goes here
    });
```

Message Returns
```
    array(4) {
      'serviceName' =>
      string(5) "MinecraftVoteSite"
      'username' =>
      string(5) "Steve"
      'address' =>
      string(9) "example.com"
      'timeStamp' =>
      string(10) "1400647120"
    }

```

### Set PSR-3 Compatible Logger (Optional)

```php
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    // create a log channel
    $logger = new Logger('Votifier');
    $logger->pushHandler(new StreamHandler('path/to/your.log', Logger::WARNING));

    $server->setLogger($logger); // Set Logger
```

### Run It

```php
    $server->run();
```

## Run with Supervisord

### Config Files
```bash
[program:votifier_vanilla]
command=php index.php vanilla
directory=/home/minecraft/http/viper-votifier
stdout_logfile=/home/minecraft/http/api/app/storage/logs/votifier_vanilla_supervisord.log
redirect_stderr=true
```
