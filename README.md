## Homesteader - Laravel Homestead Toolkit

Homesteader is a composer package meant to simplify the workflow associated with [Homestead](http://laravel.com/docs/5.0/homestead). It includes a suite of commands to manipulate Homestead's Homestead.yaml configuration file. Down the road it will assist with tasks like updating host machine hosts files, automatic reprovisioning, and fully automated scripting of Homestead configurations that can live in individual projects (think composer.json for Homestead).

This package is close to its initial release of version ```0.1.0```.

### Installation

Currently it only lives in Github since it's not officially released yet. But if you'd like to check out the development version...

```ssh
git clone -b develop https://github.com/jimmygle/Homesteader.git
cd Homesteader
composer install
./homesteader
```

### Usage

The below commands are representative of current functionality.

#### Homestead Config Manipulation

Output current homestead configuration:
```shell
homesteader config:show
```

Add new folders set:
```
homesteader config:new folder
```

Add new sites set:
```
homesteader config:new site
```

Add new database:
```
homesteader config:new database
```

Add new variable set:
```
homesteader config:new variable
```
