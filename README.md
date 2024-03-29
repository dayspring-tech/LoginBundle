# Dayspring Login Bundle
[![Build Status](https://github.com/dayspring-tech/LoginBundle/actions/workflows/symfony.yml/badge.svg)](https://github.com/dayspring-tech/LoginBundle/actions/workflows/symfony.yml)

This bundle provides basic username/password authentication, forgot/reset password, and change password.

Minimally, your `security.yml` should contain the following:
```
security:
    providers:
        dayspring:
            id: dayspring_login.user_provider
    password_hashers:
        Dayspring\LoginBundle\Model\User:
            algorithm: bcrypt
            cost: 12
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        login:
            pattern:  ^/login$
            security: false
        secured_area:
            pattern:    ^/
            user_checker: dayspring_login.user_checker
            form_login:
                check_path: _login_check
                login_path: _login
                default_target_path: /_demo/secure
                provider: dayspring
                success_handler: dayspring_login.success_handler
            logout:
                path:   _logout
                target: /
            anonymous: ~
```

### User Profiles
If your application requires additional information to be stored with the user, the recommended strategy is to create
a `UserProfile` model using Propel's inheritance features.
http://propelorm.org/Propel/documentation/09-inheritance.html#class-table-inheritance

Example:
```
<table name="user_profiles" phpName="UserProfile" idMethod="native">
  <behavior name="concrete_inheritance">
    <parameter name="extends" value="users" />
  </behavior>
  <column name="first_name" type="VARCHAR" size="100"/>
  <column name="last_name" type="VARCHAR" size="100"/>
</table>
```

