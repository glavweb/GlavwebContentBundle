Installation
============

### Get the bundle using composer

Add GlavwebContentBundle by running this command from the terminal at the root of
your Symfony project:

```bash
php composer.phar require glavweb/content-bundle
```

### Enable the bundle

To start using the bundle, register the bundle in your application's kernel class:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Glavweb\ContentBundle\GlavwebContentBundle(),
        // ...
    );
}
```
### Configure the bundle

This bundle was designed to just work out of the box. The only thing you have to enable the dynamic routes, add the following to your routing configuration file. 

```yaml
#  app/config/routing.yml

glavweb_content_block:
    resource: "@GlavwebContentBundle/Resources/config/routing.yml"
```