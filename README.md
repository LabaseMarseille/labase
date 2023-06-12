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
composer dump-env dev ## pour générer le .env.local
npm install ## ou yarn install (ou update si vous voulez récupérer les dernières versions des packages... Mais possibilité de bug ?)
npm run build ## ou yarn build
```

- Définir vos variables d'environnement dans le .env.local.php (database, ldap, ws_siamu)
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

## Installation pas à pas

### Création du projet

```bash
symfony new my_project_directory --version="6.2.*" --webapp
cd my_project_directory
```

### Installation des bundles

#### Bundle AMU CAS

Voir le README du bundle (branche v4.0)
https://carpe-koi.univ-amu.fr/amu-svc-dosi-dvpt-tous/amu-svc-dosi-dvpt-php/bundles/casbundle/-/tree/v4.0

#### Bundle AMU Role

Voir le README du bundle (branche v4.0)
https://carpe-koi.univ-amu.fr/amu-svc-dosi-dvpt-tous/amu-svc-dosi-dvpt-php/bundles/rolebundle/-/tree/v4.0

#### Charte

- Récupérer le CSS dans le skeleton
- Installer KnpMenuBundle et définir les services dans services.yaml

```yaml
app.menu_builder:
  class: App\Menu\MenuBuilder
  arguments: [ "@knp_menu.factory", "@security.authorization_checker" ]

app.sidebar_menu:
  class: Knp\Menu\MenuItem
  factory: [ "@app.menu_builder", createMainMenu ]
  arguments: [ "@request_stack" ]
  tags:
    - { name: knp_menu.menu, alias: mainMenu }

app.topbar_menu:
  class: Knp\Menu\MenuItem
  factory: [ "@app.menu_builder", createTopMenu ]
  arguments: [ "@request_stack" ]
  tags:
    - { name: knp_menu.menu, alias: topMenu }
```

- Définir les variables twig dans twig.yaml

```yaml
twig:
  default_path: '%kernel.project_dir%/templates'
  globals:
    app_title: 'Nom Application'
    app_footer: 'DirNum - Pôle Développement - Aix-Marseille Université'
```

- Récupérer les templates twig dans le skeleton (base.html.twig et menu/knp....html.twig)

(Attention, dans le base.html.twig il y a un include sur siamu.html.twig, si vous ne l'utilisez pas, il faut le
supprimer)

- Récupérer les templates d'erreurs dans templates/bundles/TwigBundle/Exception

#### SIAMU WS

- Ajouter les parameters

```yaml
  siamu:
    url: '%env(string:SIAMU_WS_URL)%'
    ws_url_app: '%env(string:SIAMU_WS_URL_APP)%'
    ws_url_app_source: '%env(string:SIAMU_WS_URL_APP_SOURCE)%'
    id_app: '%env(string:SIAMU_ID_APP)%'
    current: '%env(string:SIAMU_CURRENT)%'
```

- définir les variables d'environnement associées (normalement il y a juste l'URL et l'ID à définir ici)

```dotenv
###> WS SIAMU ###
SIAMU_WS_URL="https://.....fr/ws/"
SIAMU_WS_URL_APP="ws_appli"
SIAMU_WS_URL_APP_SOURCE="ws_appli_source"
SIAMU_ID_APP=0000
SIAMU_CURRENT=false
###< WS SIAMU ###
```

- Récupérer le service Service/Siamu.php et le subscriber EventSubscriber/SiamuSubscriber.php
- Définir le service dans services.yaml

```yaml
App\Service\Siamu:
  arguments:
    - '%env(SIAMU_WS_URL)%'
    - '%env(SIAMU_WS_URL_APP)%'
    - '%env(SIAMU_WS_URL_APP_SOURCE)%'
    - '%env(SIAMU_ID_APP)%'
    - '%env(SIAMU_CURRENT)%'
```

- Passer le service à Twig

```yaml
twig:
  default_path: '%kernel.project_dir%/templates'
  globals:
    #...
    siamu_ws: '@App\Service\Siamu'
```

- Récupérer les templates dans templates/siamu
- Récupérer SiamuController

## Upgrade manuel de Symfony 6.2 à 6.3

https://symfony.com/doc/current/setup/upgrade_minor.html

- Supprimer le bundle `sensio/framework-extra-bundle` qui est déprécié/archivé dans le composer.json. (Ce Bundle nous
  permettait des gérer les routes via les annotations avant PHP 8 et les attributs)

- Mettre à jour les packages `Symfony/*` dans le composer.json en changeant le tag `6.2.*` par `6.3.*`.

- Update via composer les paquets Symfony, puis les autres paquets

```bash
composer update "symfony/*"
composer update
```