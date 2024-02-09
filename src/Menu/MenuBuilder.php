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


        if ($this->authorizationChecker->isGranted('ROLE_USER')) {
            $menu
                ->addChild('Mon compte', ['route' => 'app_user_show'])
                ->setExtras(['icon' => 'bi bi-credit-card-2-front-fill'])
                ->setAttribute('class', 'nav-item');

            $menu
                ->addChild('Rajouter un collectif', ['route' => 'app_collectif_index'])
                ->setExtras(['icon' => 'bi bi-house-door'])
                ->setAttribute('class', 'nav-item');

            $menu
                ->addChild('Réserver un espace', ['route' => 'app_reservation_new'])
                ->setExtras(['icon' => 'bi bi-hand-index'])
                ->setAttribute('class', 'nav-item');
        }
        if ($this->authorizationChecker->isGranted('ROLE_REFPROG') or $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('Gestion des réservations', ['route' => 'app_reservation_index'])
                ->setExtras(['icon' => 'bi bi-motherboard'])
                ->setAttribute('class', 'nav-item');
        }
        if ($this->authorizationChecker->isGranted('ROLE_REFCOMM') or $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('Espace communication', ['route' => 'app_reservation_communication'])
                ->setExtras(['icon' => 'bi bi-motherboard'])
                ->setAttribute('class', 'nav-item');
        }
        if ($this->authorizationChecker->isGranted('ROLE_REFBAR') or $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('Espace refBar', ['route' => 'app_reservation_referentbar'])
                ->setExtras(['icon' => 'bi bi-motherboard'])
                ->setAttribute('class', 'nav-item');
        }
        if ($this->authorizationChecker->isGranted('ROLE_REFBASE') or $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu
                ->addChild('Espace refBase ', ['route' => 'app_reservation_referentbase'])
                ->setExtras(['icon' => 'bi bi-motherboard'])
                ->setAttribute('class', 'nav-item');
        }

        return $menu;
    }

    public function createTopMenu(RequestStack $requestStack): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', ' navbar-nav');
/*
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
*/
        $menu
            ->addChild('Logout', ['route' => 'cas_bundle_logout'])
            ->setExtras(['icon' => 'bi bi-box-arrow-right'])
            ->setAttribute('class', 'nav-item')
            ->setLabel('app_logout');

        return $menu;
    }
}
