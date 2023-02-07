# SymfonySkeleton62

Ce projet est un squelette de projet Symfony 6.2.

Les bundle utilisés sont :

- CAS de la commission Européenne pour l'authentification
- ROLE pour utiliser les rôles via GROUPIE, LDAP, Login, ou DQL
- Charte Graphique AMU

Version de PHP minimum : 8.1

```bash
$ php -v
PHP 8.1.11 (cli) (built: Sep 29 2022 19:44:28) (NTS)
Copyright (c) The PHP Group
```

## Installation complète

Cette installation comprend :

- Bundle EcPhp/CasBundle
- Bundle AmuCas
- Bundle AmuRole
- La charte (Menu...)
- Le ws SIAMU

```bash
git clone ...
cd ## dans le dossier
composer update ## ou install si vous avez des bugs
cp .env .env.local
npm install ## On peut tenter une update 
npm run build ## dev
```

- Définir vos variables d'environnement dans le .env.local (database, ldap, ws_siamu)
- Définir vos roles dans config/packages/amu_role.yaml
- Définir vos variables twig (nom de l'appli...) dans config/packages/twig.yaml
- Définir vos access_control suivant vos routes dans config/packages/security.yaml
- Ajouter vos menu dans src/Menu/MenuBuilder.php

On lance le serveur de développement
```bash
symfony serve
```

Avec la configuration par défaut, l'accueil est public et non-authentifié.
Pour lancer l'authentification, allez sur /admin.


- Pour tester l'impersonification, dans l'url, ajouter ?_switch_user=uid 
- Pour quitter l'impersonification, dans l'url, ajouter ?_switch_user=_exit 

## (Doc incomplète : Ne pas faire !! ) Installation pas à pas

### Création du projet

```bash
symfony new my_project_directory --version="6.2.*" --webapp
cd my_project_directory
```

### Installation des bundles

#### CAS

https://ecphp-cas-bundle.readthedocs.io/en/latest/pages/installation.html

On installe le bundle

```bash
composer require ecphp/cas-bundle nyholm/psr7
## yes pour les recipes
```

On mets à jour le fichier config/packages/dev/cas_bundle.yaml

```yaml
cas:
  base_url: https://ident.univ-amu.fr/cas
  protocol:
    login:
      path: /login
      allowed_parameters:
        - service
        - renew
        - gateway
      default_parameters:
        service: app_home
    serviceValidate:
      path: /serviceValidate
      allowed_parameters:
        - format
        - service
        - ticket
      default_parameters:
        format: XML
    logout:
      path: /logout
      allowed_parameters:
        - service
      default_parameters:
        service: app_home
    proxy:
      path: /proxy
      allowed_parameters:
        - targetService
        - pgt
    proxyValidate:
      path: /proxyValidate
      allowed_parameters:
        - format
        - service
        - ticket
      default_parameters:
        format: XML
```

On mets à jour le security.yaml

```yaml
security:
  firewalls:
    dev:
      pattern: ^/(_(profiler|wdt)|css|images|js)/
      security: false
    main:
      provider: cas
      custom_authenticator: EcPhp\CasBundle\Security\CasAuthenticator
      form_login:
        check_path: cas_bundle_login
        login_path: cas_bundle_login
      entry_point: form_login
      logout:
        path: security_logout
        target: homepage
        csrf_parameter: logout
        csrf_token_generator: security.csrf.token_manager

  access_control:
    - { path: ^/cas, roles: PUBLIC_ACCESS }
    - { path: ^/, roles: ROLE_CAS_AUTHENTICATED }

when@test:
  security:
    password_hashers:
      Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface:
        algorithm: auto
        cost: 4 # Lowest possible value for bcrypt
        time_cost: 3 # Lowest possible value for argon
        memory_cost: 10 # Lowest possible value for argon
```

Ensuite on override le LOGIN pour que le CAS nous redirige sur la page que l'on cherchait à accéder

dans src/Controller/Security, créer un fichier Login.php

```php
<?php

declare(strict_types=1);

namespace App\Controller\Security;

use EcPhp\CasLib\CasInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cas/login', name: 'cas_bundle_login')]
final class Login extends AbstractController
{
    public function __invoke(
        Request      $request,
        CasInterface $cas,
        Security     $security
    ): RedirectResponse|ResponseInterface
    {
        $parameters = $request->query->all() + [
                'renew' => null !== $security->getUser(),
                'service' => $request->getSession()->get('_security.main.target_path'),
            ];

        $response = $cas->login($parameters);

        return null === $response
            ? new RedirectResponse('/')
            : $response;
    }
}

```

dans src/Controller/Security, créer un fichier Logout.php

```php
<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ecphp
 */

declare(strict_types=1);

namespace App\Controller\Security;

use EcPhp\CasLib\CasInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/cas/logout', name: 'cas_bundle_logout')]
final class Logout extends AbstractController
{
    public function __invoke(
        Request               $request,
        CasInterface          $cas,
        TokenStorageInterface $tokenStorage
    ): RedirectResponse|ResponseInterface
    {
        // On ne veut pas être redirigé après la deconnexion CAS
        $response = $cas->logout([...$request->query->all(), 'service' => '']);

        if (null === $response) {
            return new RedirectResponse('/');
        }

        $tokenStorage->setToken();

        return $response;
    }
}

```

#### Bundle Role

Si vous avez besoin d'utiliser GROUPIE, il faut installer le bundle ldap

```bash
composer require symfony/ldap
```

