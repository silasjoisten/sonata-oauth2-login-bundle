# Exozet/Oauth2LoginBundle

### Installation

```console
    composer require silasjoisten/oauth2login-bundle
```

Register Bundle in **app/AppKernel.php**:
```php
 class AppKernel extends Kernel
 {
     public function registerBundles()
     {
        //...
        new Exozet\Oauth2LoginBundle\ExozetOauth2LoginBundle(),
//...
```

### Configuration

Include the Routing:
```yml
exozet_oauth:
    resource: "@ExozetOauth2LoginBundle/Resources/config/routing.xml"
```

Configure the HWIOauthBundle:

in security.yml:
```yml
security:
   providers:
      hwi:
         id: exozet.oauth2.user.provider

         #...

   firewalls:
      your_firewall:
         oauth:
            resource_owners:
               google:             "/login/check-google"
            login_path:        /admin/login                 # For Sonata Admin
            use_forward:       false
            default_target_path: /admin/dashboard           # For Sonata Admin
            failure_path:      /admin/login                 # For Sonata Admin

                oauth_user_provider:
                 service:  exozet.oauth2.user.provider
```
