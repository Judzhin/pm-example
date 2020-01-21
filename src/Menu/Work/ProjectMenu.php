<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Menu\Work;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class ProjectMenu
 * @package App\Menu\Work
 */
class ProjectMenu
{
    /** @var FactoryInterface */
    private $factory;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /**
     * SidebarMenu constructor.
     * @param FactoryInterface $factory
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->factory = $factory;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param array $options
     * @return ItemInterface
     */
    public function build(array $options): ItemInterface
    {
//        /** @var ItemInterface $menu */
//        $menu = $this->factory->createItem('root')
//            ->setChildrenAttributes(['class' => 'nav']);
//
//        $menu
//            ->addChild('Dashboard', ['route' => 'pm_home'])
//            ->setExtra('icon', 'nav-icon icon-speedometer')
//            ->setAttribute('class', 'nav-item')
//            ->setLinkAttribute('class', 'nav-link');
//
//        $menu->addChild('Work')
//            ->setAttribute('class', 'nav-title');
//
//        if ($this->authorizationChecker->isGranted('ROLE_WORK_MANAGE_MEMBERS')) {
//            $menu
//                ->addChild('Members', ['route' => 'pm_work_members'])
//                ->setExtra('routes', [
//                    ['route' => 'pm_work_members'],
//                    ['pattern' => '/^pm_work_member.?/']
//                ])
//                ->setExtra('icon', 'nav-icon icon-people')
//                ->setAttribute('class', 'nav-item')
//                ->setLinkAttribute('class', 'nav-link');
//        }
//
//        $menu
//            ->addChild('Control')
//            ->setAttribute('class', 'nav-title');
//
//        if ($this->authorizationChecker->isGranted('ROLE_MANAGE_USERS')) {
//            $menu
//                ->addChild('Users', ['route' => 'pm_users'])
//                ->setExtra('icon', 'nav-icon icon-people')
//                ->setExtra('routes', [
//                    ['route' => 'pm_users'],
//                    ['pattern' => '/^pm_user.?/']
//                ])
//                ->setAttribute('class', 'nav-item')
//                ->setLinkAttribute('class', 'nav-link');
//        }
//
//        // $menu
//        //     ->addChild('Profile', ['route' => 'pm_profile'])
//        //     ->setExtra('icon', 'nav-icon icon-user')
//        //     ->setExtra('routes', [
//        //         ['route' => 'pm_profile'],
//        //         ['pattern' => '/^profile.+/']
//        //     ])
//        //     ->setAttribute('class', 'nav-item')
//        //     ->setLinkAttribute('class', 'nav-link');
//        return $menu;
    }

}