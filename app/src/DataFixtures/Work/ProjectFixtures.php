<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\DataFixtures\Work;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Entity\Work\Member;
use App\Entity\Work\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProjectFixtures
 * @package App\DataFixtures\Work
 */
class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \Throwable
     */
    public function load(ObjectManager $manager): void
    {
        /** @var Member $member */
        $member = $this->getReference(MemberFixtures::class);

        /** @var string $projectName */
        foreach (['First Project', 'Second Project', 'Third Project'] as $key => $projectName) {
            $manager->persist($project = self::create($projectName));

            if (!$key) {
                foreach (['Development', 'Marketing'] as $depName) {
                    $project->addDepartment($department =  (new Project\Department)
                        ->setName($depName));

                    // $project->addMember($member, [$department]);
                }
            }

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

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function getDependencies():array
    {
        return [
            MemberFixtures::class
        ];
    }
}