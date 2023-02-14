<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

final class MenuBuilder implements ContainerAwareInterface

{
    use ContainerAwareTrait;

    public function __construct(
        private readonly FactoryInterface     $factory,
        private readonly AuthorizationChecker $authorizationChecker
    )
    {
    }

    // https://github.com/KnpLabs/KnpMenu/blob/master/doc/01-Basic-Menus.md
    public function createMainMenu(RequestStack $requestStack): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', ' navbar-nav');

        $menu
            ->addChild('app_home', ['route' => 'app_index'])
            ->setExtras(['icon' => 'bi bi-house-door'])
            ->setAttribute('class', 'nav-item');

        if ($this->authorizationChecker->isGranted('ROLE_CAS_AUTHENTICATED')) {
            $menu
                ->addChild("Administration", ['route' => 'app_admin_index'])
                ->setExtras(['icon' => 'bi bi-file-lock', 'class' => 'navitem'])
                ->setAttribute('class', 'nav-item');
        }

        return $menu;
    }

    public function createTopMenu(RequestStack $requestStack): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', ' navbar-nav');

        $menu
            ->addChild('Menu 1', ['route' => 'app_index'])
            ->setExtras(['icon' => 'bi bi-1-circle-fill'])
            ->setAttribute('class', 'nav-item');
        $menu
            ->addChild('Menu 2', ['route' => 'app_index'])
            ->setExtras(['icon' => 'bi bi-2-circle-fill'])
            ->setAttribute('class', 'nav-item');

        $menu
            ->addChild('ENT')
            ->setUri('https://ent.univ-amu.fr')
            ->setAttribute('class', 'nav-item');

        $menu
            ->addChild('Logout', ['route' => 'cas_bundle_logout'])
            ->setExtras(['icon' => 'bi bi-box-arrow-right'])
            ->setAttribute('class', 'nav-item')
            ->setLabel('app_logout');

        return $menu;
    }
}
