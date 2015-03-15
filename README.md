## Homesteader - Laravel Homestead Toolkit

[![Build Status](https://travis-ci.org/jimmygle/Homesteader.svg?branch=master)](https://travis-ci.org/jimmygle/Homesteader)

Homesteader is a composer package with automation in mind. It's meant to simplify the workflow associated with [Homestead](http://laravel.com/docs/5.0/homestead), and it includes a suite of commands to manipulate Homestead's  configuration file. Down the road it will assist with tasks like updating host machine hosts files, automatic reprovisioning, and fully automated scripting of Homestead configurations that can live in individual projects (think composer.json for Homestead).

### Why
Homestead is a great use of a couple [virtualization](http://vagrantup.com/) [tools](https://www.virtualbox.org/). But when using it within a team environment, the less steps needed to get the dev environment setup, the better. So this package attempts to simplify the homestead workflow even more than it already is. The ultimate goal is to ship a "homesteader" config file with a project and have it setup the local Homestead environment with a single command.

### Installation

This package requires [Composer](http://getcomposer.org) and [Homestead](http://laravel.com/docs/5.0/homestead).

```ssh
composer global require "jimmygle/homesteader=~1.0"
```

### Usage

The below commands are representative of current functionality.

#### Homestead Config Manipulation

Global options:
* ```--file (-f)  [path/to/Homestead.yaml]```   - Custom homestead config file path
* ```--no-interaction (-n)```   - Runs unattended (some command specific options will be required)

Output current homestead configuration:
```shell
homesteader config:show
```

Add new folders set:
```
homesteader config:new folder [--host /path/on/local] [--homestead /path/in/homestead]
```

Add new sites set:
```
homesteader config:new site [--domain example.local] [--homestead /path/in/homestead/public]
```

Add new database:
```
homesteader config:new database [--name example_db]
```

Add new variable set:
```
homesteader config:new variable [--key VAR] [--value test]
```
