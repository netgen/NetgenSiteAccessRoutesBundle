Netgen Siteaccess Routes Bundle
===============================

[![Code Coverage](https://img.shields.io/codecov/c/github/netgen/NetgenSiteAccessRoutesBundle.svg?style=flat-square)](https://codecov.io/gh/netgen/NetgenSiteAccessRoutesBundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/netgen/NetgenSiteAccessRoutesBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/netgen/NetgenSiteAccessRoutesBundle)
[![Downloads](https://img.shields.io/packagist/dt/netgen/siteaccess-routes-bundle.svg?style=flat-square)](https://packagist.org/packages/netgen/siteaccess-routes-bundle)
[![Latest stable](https://img.shields.io/packagist/v/netgen/siteaccess-routes-bundle.svg?style=flat-square)](https://packagist.org/packages/netgen/siteaccess-routes-bundle)
[![License](https://img.shields.io/github/license/netgen/NetgenSiteAccessRoutesBundle.svg?style=flat-square)](LICENSE)

Netgen Siteaccess Routes Bundle is an Ibexa Platform bundle which allows you to specify in which siteaccesses or siteaccess groups can a route be used.

By default, all routes are accessible in all siteaccesses. To specify in which siteaccess a route can be used, you will need to add an `allowed_siteaccess` param to the `defaults` section of a route or route import:

```yml
netgen_site_blog:
    path: /blog
    controller: "netgen_site.controller.blog:blogAction"
    methods: [GET]
    defaults:
        allowed_siteaccess: cro
```

or

```yml
_netgen_site:
    resource: "@NetgenSiteBundle/Resources/config/routing.yaml"
    defaults:
        allowed_siteaccess: cro
```

You can even specify an array of siteaccesses, or use siteaccess groups:

```yml
defaults:
    allowed_siteaccess: [backend_group, cro]
```

As a special case, you can use `_default` keyword to signal that the route is also accessible in the default siteaccess, whichever siteaccess that may be.

```yml
defaults:
    allowed_siteaccess: [cro, _default]
```

If the route is not available in current siteaccess, a 404 Not Found response will be returned.

Installation
------------

Use Composer:

```bash
composer require netgen/siteaccess-routes-bundle
```

Activate in `config/bundles.php`:

```php
Netgen\Bundle\SiteAccessRoutesBundle\NetgenSiteAccessRoutesBundle::class => ['all' => true],
```

That's it. Configure the routes and go about your day.

License
-------

[GNU General Public License v2](LICENSE)
