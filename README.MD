
[![Troopers](https://cloud.githubusercontent.com/assets/618536/18787530/83cf424e-81a3-11e6-8f66-cde3ec5fa82a.png)](https://gitlab.troopers.agency/robinstraub/CronBundle)
[![Twitter Follow](https://img.shields.io/twitter/follow/troopersagency.svg?style=social&label=Follow%20Troopers)](https://twitter.com/troopersagency)

CronBundle
=============

## Synopsis

This bundle is used to define symfony commands as cron using a yaml configuration file.

## Install
run `composer require robinstraub/cron-bundle` 

If you are not using flex, registrer the bundle in AppKernel
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
```yml
troopers_cron:
    reporting:
        api:
            url: 'http://monitoring_api.local/api/cron'
            api_key: 'myapikey'
            format: form
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
### Reporting
The `reporting` key let you use a reporting tool in order to keep track of tasks execution.
The only tool available for now is the `api`.

#### api
- `url`: api url (base uri and api route)
- `api_key`: optional api_key used for authentication
- `format`: format used for the data. Only form is implemented. Default value is form

### tasks
The list of commands to execute.

- `command`: command to execute
- `schedule`: cron schedule
- `arguments`: list of the command's arguments
 

## Mise en place des cron
Add the following line to your crontab:
```
* * * * * /path/to/symfony/install/app/console cron:run 1>> /dev/null 2>&1
```
