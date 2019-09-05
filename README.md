[![Build Status](https://travis-ci.com/Kynno/SmartBotsBundle.svg?token=zRjaMaujwSVSWE7UcXcX&branch=master)](https://travis-ci.com/Kynno/SmartBotsBundle)

SmartBots
============
[SmartBots](https://www.mysmartbots.com/) is a service provider which allows you to manage your Second Life group and/or control your Second Life bots.

All SmartBots services use the abilities of SL bots: group inviters, group chat translators, automatic notice delivery system etc.

You can find more informations about them at http://www.mysmartbots.com/docs/SmartBots_Documentation

Installation 
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require kynno/smartbots-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require kynno/smartbots-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Kynno\SmartBotsBundle\SmartBotsBundle::class => ['all' => true],
];
```


Usage
=======
The list of the commands available are available on the official website of SmartBots: https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands

In order to use this bundle, you will need to have your [developer API Key](https://www.mysmartbots.com/process/adminbot.html).

This is an example of configuration for `config/packages/smartbots.yaml`,

```yaml
kynno_smartbots:
    api_key: <your_api_key>
    bots:
        Kynno:
            name: "KynnoSystem Resident"
            botSecret: pwd
        Leekyn:
            name: "Leekyn Resident"
            botSecret: pwd
```

Under the bots key, you can have multiple bots. In this example, `Kynno` and `Leekyn` are the IDs of the bots.
Using the `SmartBots` service, you need to use these IDs instead of the full name of your bots.

Of course, you can have only one bot.

---
Once you configured your credentials, you can start using the service.

```php
$this->smartBots->im('Heyter Nitely', "Hey, it's working!");
```
Note: If you don't select a bot before a command, it will auto select the first one. 

Don't hesitate to open the file `AbstractSmartBotsCommands.php` to see how to use the different commands.
