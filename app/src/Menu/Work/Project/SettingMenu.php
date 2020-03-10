<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Menu\Work\Project;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class SettingMenu
 * @package App\Menu\Work\Project
 */
class SettingMenu
{
    /** @var FactoryInterface */
    private $factory;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /**
     * SettingMenu constructor.
     *
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
        /** @var ItemInterface $menu */
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav nav-tabs mb-4']);

        if ($this->authorizationChecker->isGranted('ROLE_WORK_MANAGE_PROJECTS')) {
            $menu
                ->addChild('Common', [
                    'route' => 'pm_work_project_setting',
                    'routeParameters' => ['id' => $options['project_id']]
                ])
                ->setExtra('routes', [
                    ['route' => 'pm_work_project_setting'],
                    ['route' => 'pm_work_project_setting'],
                ])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');

            $menu
                ->addChild('Departments', [
                    'route' => 'pm_work_project_setting_departments',
                    'routeParameters' => ['project_id' => $options['project_id']]
                ])
                ->setExtra('routes', [
                    ['route' => 'pm_work_project_setting_departments'],
                    ['pattern' => '/^pm_work_project_setting_departments\..+/']
                ])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');

            $menu
                ->addChild('Members', [
                    'route' => 'pm_work_project_setting_members',
                    'routeParameters' => ['project_id' => $options['project_id']]
                ])
                ->setExtra('routes', [
                    ['route' => 'pm_work_project_setting_members'],
                    ['pattern' => '/^pm_work_project_setting_members\..+/']
                ])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');
        }

        return $menu;
    }

}