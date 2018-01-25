
[![Troopers](https://cloud.githubusercontent.com/assets/618536/18787530/83cf424e-81a3-11e6-8f66-cde3ec5fa82a.png)](https://gitlab.troopers.agency/robinstraub/CronBundle)
[![Twitter Follow](https://img.shields.io/twitter/follow/troopersagency.svg?style=social&label=Follow%20Troopers)](https://twitter.com/troopersagency)

CronBundle
=============

## Synopsis

This bundle is used to define symfony commands as cron using a yaml configuration file.

## Install

`composer require robinstraub/cron-bundle` and registrer in AppKernel

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Troopers\CronBundle\CronBundle(),
            ...
        );

        return $bundles
```

## Configuration
Add your configuration in `config.yml`. See the following example on for how to customize your commands
```
cron:
    monitor:
        url: 'https://monitor.local'
        api_key: 'yourapikey'
    tasks:
        - command: 'help'
          schedule: '* * * * *'
          arguments:
            command_name: 'cache:clear'
        - command: 'cache:clear'
          schedule: '@daily'
          arguments: 
            --env: 'prod'
            --no-debug: null
```
### Mise en place des cron
Add the following line to your crontab:
```
* * * * * /path/to/symfony/install/app/console cron:run 1>> /dev/null 2>&1
```
