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

    // Doc sur les méthodes du menu https://github.com/KnpLabs/KnpMenu/blob/master/doc/01-Basic-Menus.md
    // Menu responsive (mais KO) https://www.pierre-giraud.com/bootstrap-apprendre-cours/barre-navigation/
    public function createMainMenu(RequestStack $requestStack): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-sidebar navbar-nav justify-content-center');
        $activeFound = false;
        // Accueil
        $menu
            ->addChild('Accueil', ['route' => 'app_index'])
            ->setExtras(['icon' => 'bi bi-house-door'])
            ->setAttribute('class', 'nav-item');
        // Admin
        /*        if ($this->authorizationChecker->isGranted('ROLE_USER')) {
                    $menu->addChild('menu.admin', ['route' => 'default_admin'])
                        ->setExtras(['icon' => 'bi bi-wrench', 'class' => 'navitem']);
                    $menu['menu.admin']->setAttribute('class', 'nav-item');

                    if (str_starts_with($requestStack->getCurrentRequest()->getPathInfo(), '/admin')) {
                        $menu->getChild('menu.admin')->setCurrent(true);
                        $menu['menu.admin']->setAttribute('class', 'nav-item');
                        $activeFound = true;
                    };
                }*/

        if ($this->authorizationChecker->isGranted('ROLE_CAS_AUTHENTICATED')) {
            $menu
                ->addChild('Admin', ['route' => 'app_admin_index'])
                ->setExtras(['icon' => 'bi bi-file-lock', 'class' => 'navitem'])
                ->setAttribute('class', 'nav-item');
        }

        return $menu;
    }
}
