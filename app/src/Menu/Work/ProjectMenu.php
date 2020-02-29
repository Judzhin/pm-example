<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Menu\Work;

use App\Entity\Work\Project;
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

        /** @var Project $project */
        $project = $options['project'];

        $menu
            ->addChild('Dashboard', [
                'route' => 'pm_work_project',
                'routeParameters' => ['id' => $project->getId()]
            ])
            ->setExtra('routes', [
                ['route' => 'pm_work_project'],
                ['pattern' => '/^pm_work_project$/']
            ])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        //$menu
        //    ->addChild('Actions', [
        //        'route' => 'work.projects.project.actions',
        //        'routeParameters' => ['project_id' => $options['project_id']]
        //    ])
        //    ->setAttribute('class', 'nav-item')
        //    ->setLinkAttribute('class', 'nav-link');
        //
        //$menu
        //    ->addChild('Tasks', [
        //        'route' => 'work.projects.project.tasks',
        //        'routeParameters' => ['project_id' => $options['project_id']]
        //    ])
        //    ->setExtra('routes', [
        //        ['route' => 'work.projects.project.tasks'],
        //        ['pattern' => '/^work.projects.project.tasks\..+/']
        //    ])
        //    ->setAttribute('class', 'nav-item')
        //    ->setLinkAttribute('class', 'nav-link');
        //
        //$menu
        //    ->addChild('Calendar', [
        //        'route' => 'work.projects.project.calendar',
        //        'routeParameters' => ['project_id' => $options['project_id']]
        //    ])
        //    ->setAttribute('class', 'nav-item')
        //    ->setLinkAttribute('class', 'nav-link');
        //

        if ($this->authorizationChecker->isGranted('ROLE_WORK_MANAGE_PROJECTS')) {
            $menu
                ->addChild('Settings', [
                    'route' => 'pm_work_project_setting',
                    'routeParameters' => ['id' => $project->getId()]
                ])
                ->setExtra('routes', [
                    ['route' => 'pm_work_project_setting'],
                    ['pattern' => '/^pm_work_project_setting\.?/']
                ])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');
        }

        return $menu;
    }

}