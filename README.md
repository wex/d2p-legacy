# wex-d2p

D2P is a lightweight PHP framework which can be used as Composer project to create your own software.

# How to get it?

  - You can use `git clone` to get it from GitHub
  - You can use Composer to install it as skeleton project: 
    `composer create-project wex/d2p:dev-master --repository-url=https://wex.fi/d2p/`

# Requirements

D2P is requires PHP7.0 and is built over some other packages.

* [zendframework/zend-config] - Used in core configuration
* [zendframework/zend-db] - Used in ActiveRecord
* [zendframework/zend-diactoros] - Used in core request-response -routing
* [ramsey/uuid] - Used for UUID generation
* [filp/whoops] - Used for debugging
* [aura/router] - Used for routing
* [leafo/scssphp] - Used for realtime SCSS compiling
* [matthiasmullie/minify] - Used for realtime JS / CSS minifying

# How to help?

At this point D2P is a one-man show. Architecture WILL be changed and some parts will be rewritten for sure.

# NOTICE!
Please do not use D2P in production before version `1.0.0` is released!

License
----

MIT