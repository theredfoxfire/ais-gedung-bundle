# ais-gedung-bundle
gedung Bundle For AIS. I use Symfony 2.7.4 in my kit. In case if you want to install Symfony follow the URL below:

[Symfony 2.7](http://symfony.com/doc/2.7/book/installation.html)


## Usage

I assume you already have composer on your dev environment. If not, please visit this URL:


[Getting started with Composer](https://getcomposer.org/doc/00-intro.md)


Add the following inside require tag in your root composer.json file:

```json
{
    "require": {
      "ais/gedungbundle" : "dev-master"
    },
}
```
Run composer update, and wait until composer update is finished.
```
php composer.phar update
```
Registering the bundle into your AppKernel.php 

Once the composer update is finished. If you not yet install NelmioApiDocBundle before, you need registering it too. 

Because this bundle require NelmioApiDocBundle to see the API doc. I also use JMS Serializer and FOSRestBundle.

```php
<?php
// app/AppKernel.php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
        ...
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Ais\GedungBundle\AisGedungBundle(),
        );
        ...

        return $bundles;
    }
}
```

Import the route to your app/config/routing.yml

```yaml
  ais_gedungs:
    type: rest
    prefix: /api
    resource: "@AisGedungBundle/Resources/config/routes.yml"
  
  NelmioApiDocBundle:
    resource: "@NelmioApiDocBundle/Resources/config/routing.yml"
    prefix:   /api/doc
```

## See what in the inside
Now you may see the available API by access your url dev

```
ex: http://localhost/web/app_dev.php/api/doc
```
Find a typo? just ask me for PR. If you find some error please help me to fix it by email me to vizzlearn@gmail.com
