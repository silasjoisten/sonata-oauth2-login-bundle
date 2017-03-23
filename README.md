# Exozet/Oauth2LoginBundle

### Installation

```console
    composer require silasjoisten/oauth2login-bundle
```


Register Bundle in **app/AppKernel.php**

```php
 class AppKernel extends Kernel
 {
     public function registerBundles()
     {
        //...

        new Exozet\Oauth2LoginBundle\ExozetOauth2LoginBundle(),
//...
```

