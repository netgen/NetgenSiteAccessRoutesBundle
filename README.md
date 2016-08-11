Netgen Siteaccess Routes Bundle
===============================

[![Build Status](https://img.shields.io/travis/netgen/NetgenSiteAccessRoutesBundle.svg?style=flat-square)](https://travis-ci.org/netgen/NetgenSiteAccessRoutesBundle)
[![Downloads](https://img.shields.io/packagist/dt/netgen/siteaccess-routes-bundle.svg?style=flat-square)](https://packagist.org/packages/netgen/siteaccess-routes-bundle)
[![Latest stable](https://img.shields.io/packagist/v/netgen/siteaccess-routes-bundle.svg?style=flat-square)](https://packagist.org/packages/netgen/siteaccess-routes-bundle)
[![License](https://img.shields.io/packagist/l/netgen/siteaccess-routes-bundle.svg?style=flat-square)](https://packagist.org/packages/netgen/siteaccess-routes-bundle)

Netgen Siteaccess Routes Bundle is an eZ Publish / eZ Platform bundle which allows you to specify in which siteaccesses or siteaccess groups can a route be used.

To specify in which siteaccess a route can be used, you will need to add an `allowed_siteaccess` param to `defaults` section of a route or route import.

```
netgen_site_blog:
    path: /blog
    methods: [GET]
    defaults:
        _controller: "netgen_site.controller.blog:blogAction"
        allowed_siteaccess: cro
```

or

```
_netgen_site:
    resource: "@NetgenSiteBundle/Resources/config/routing.yml"
    defaults:
        allowed_siteaccess: cro
```

You can even specify an array or siteaccesses, or use siteaccess groups:

```
defaults:
    allowed_siteaccess: [frontend_group, mysiteaccess]
```

As a special case, you can use `_default` keyword to signal that the route is also accessible in the default siteaccess, whichever siteaccess that may be.

```
defaults:
    allowed_siteaccess: [cro, _default]
```

Installation
------------

Use Composer:

```
composer require netgen/siteaccess-routes-bundle:^1.0
```

Activate in kernel:

```
$bundles[] = new Netgen\Bundle\SiteAccessRoutesBundle\NetgenSiteAccessRoutesBundle();
```

That's it. Configure the routes and go about your day.

License
-------

[GNU General Public License v2](LICENSE)
