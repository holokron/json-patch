# Json Patch 

Simple library to handle Json Patch requests according to [RFC 6902](https://tools.ietf.org/html/rfc6902)

Other libraries are just changing one JSON string to another but the main purpose of this code is to execute particular action (callback defined in configuration) given particular JSON. 

## Getting started

### Perequisites

- PHP >= 7.0.0

### Installing

```bash
composer require holokron/json-patch
```

### Example

#### Configuration

Our example class with methods which should be called:

```php
<?php

namespace Example\Handler;

use Example\Entity\Account;

class UserHandler
{
    public function add($value) 
    {
        $value = json_encode($value);
        echo "UserHandler::add($value)\n";
    }

    public function remove(int $id)
    {
        echo "UserHandler::remove($id)\n";
    }

    public function replace(string $id, array $value)
    {
        $value = json_encode($value);
        echo "UserHandler::replace($id)($value)\n";
    }
}

```

Configuration of our patcher:

```php
<?php

use Example\Handler\UserHandler;
use Holokron\JsonPatch as JsonPatch;


$accountHandler = new UserHandler();

$builder = new JsonPatch\Definition\Builder();
$definitions = $builder
    ->op('add')
        ->path('/users')
        ->callback([$handler, 'add'])
        ->add()
    ->op('remove')
        ->path('/users/:userId')
        ->callback([$handler, 'remove'])
        ->requirement('userId', '[1-9]+\d*')
        ->add()
    ->op('replace')
        ->path('/users/:userId')
        ->callback([$handler, 'replace'])
        ->requirement('userId', '\w+')
        ->add()
    ->get();

$patcherFactory = new JsonPatch\Factory();
$patcher = $patcherFactory
    ->setMatcher(new JsonPatch\Matcher\Matcher($definitions))
    ->create();

```

Then applying our example JSON:

```json
[
    {
        "op": "add",
        "path": "/users",
        "value": {
            "name": "super handler"
        }
    },
    {
        "op": "remove",
        "path": "/users/123"
    },
    {
        "op": "replace",
        "path": "/users/456",
        "value": {
            "foo": "bar"
        }
    }
]
```

We will get result:
```bash
UserHandler::add({"name":"super handler"})
UserHandler::remove(123)
UserHandler::replace(456)({"foo":"bar"})
```