Modules
==

## Files
All modules are located in `app/modules/`.

## Loading module
Optimization means that you can not use moduled dynamicly.

If we got `app/modules/users/` it should contain following files:
* `route.php`
* `Module.php`
* `controllers/`
* `controllers/IndexController.php`
* `views/`
* `views/index/index.phtml`
* `models/`
* `models/User.php`

## Handler `Module.php`
This class should extend `\Wex\Module`.

Abstract methods:
* `boot(\Wex\App $application) : void`

Other methods
* `install()`
* `uninstall()`