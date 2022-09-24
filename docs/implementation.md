# Implementation

## First Steps

Obtain and deploy the **PHP-MQ server** to your infrastructure.

1. PHP source can be downloaded from [PHP-MQ Server GitHub](https://github.com/michaelrk02/phpmq-server).
2. Point the HTTP server document root to the **public/** directory.
3. Create a file named **config.php** from the existing template provided by the project in its root directory and edit it accordingly:
    * Generate a random and secure secret key and paste it to the `PHPMQ_SECRET_KEY` entry.
    * Configure another options based on your preferences.
4. Run the database migrations in order from version 1.0 with **-up.sql** suffix in the **database/** directory.

## Integration

1. From your application, add `michaelrk02/php-mq` as a dependency for the Composer.
2. Instantiate `\Michaelrk02\PhpMq\Listener` and `\Michaelrk02\PhpMq\Broadcaster` objects.
    * Provide the server URL according to the previously deployed PHP-MQ server.
    * Provide the secret key according to the previously set secret key.
3. See the API reference for further details on the implementation.

## Example

Initiate SSE connection, within **listen.php**:

```php
use Michaelrk02\PhpMq\Listener;

// ...

define('MQ_SERVER_URL', 'https://mq.example.com/');
define('MQ_SECRET_KEY', 'SomeVerySecretKey');

// ...

$listener = new Listener(MQ_SERVER_URL, MQ_SECRET_KEY);

// ...

$channelId = $_GET['channel'];

$listener->listen($channelId);
```

Listen SSE events, within **listener.js**:

```javascript
var sse = new EventSource('listen.php?channel=' + channelId);
var clientId = null;
sse.addEventListener('phpmq_client_id', function(event) {
    clientId = parseInt(event.data);

    // PING every 15 seconds
    setInterval(function() {
        var ping = new XMLHttpRequest();
        ping.open('GET', 'ping.php?client=' + clientId);
        ping.send();
    }, 15000);
});
sse.addEventListener('echo', function(event) {
    console.log('Echo from server: ' + event.data);
})
```

Send PING request, within **ping.php**:

```php
$clientId = $_GET['client'];

$listener->ping($clientId);
```

Broadcast SSE message, within **broadcaster.php**

```php
use Michaelrk02\PhpMq\Broadcaster;

// ...

$broadcaster = new Broadcaster(MQ_SERVER_URL, MQ_SECRET_KEY);

// ...

$channelId = $_POST['channel'];
$data = $_POST['data'];

$broadcaster->broadcast($channelId, 'echo', $data);
```
