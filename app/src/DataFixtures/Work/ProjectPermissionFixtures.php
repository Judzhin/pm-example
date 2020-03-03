<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\DataFixtures\Work;

use App\Entity\Work\Project;
use App\Model\Work\Project\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProjectPermissionFixtures
 *
 * @package App\DataFixtures\Work
 */
class ProjectPermissionFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws \Throwable
     */
    public function load(ObjectManager $manager): void
    {
        /**
         * @var string $role
         * @var array $permissions
         */
        foreach (['Guest' => [], 'Manager' => [Permission::MANAGE_PROJECT_MEMBERS]] as $role => $permissions) {
            $manager->persist(self::create($role, $permissions));
        }

        $manager->flush();
    }

    /**
     * @param $name
     * @param $permissions
     * @return Project\Role
     */
    private static function create($name, $permissions): Project\Role
    {
        return (new Project\Role)
            ->setName($name)
            ->setPermissions(new ArrayCollection(array_map(function (string $name) {
                return new Permission($name);
            }, $permissions)));
    }
}