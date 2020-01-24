<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\DataFixtures\Work;

use App\Entity\Work\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProjectFixtures
 * @package App\DataFixtures\Work
 */
class ProjectFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var string $name */
        foreach (['First Project', 'Second Project', 'Third Project'] as $name) {
            $manager->persist(self::create($name));
        }

        $manager->flush();
    }

    /**
     * @param $name
     * @param int $sort
     * @return Project
     */
    private static function create($name, $sort = 10): Project
    {
        return (new Project)
            ->setName($name)
            ->setSort($sort);
    }
}