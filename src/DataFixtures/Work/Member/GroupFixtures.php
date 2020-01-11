<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\DataFixtures\Work\Member;

use App\Entity\Work\Member\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class GroupFixtures
 * @package App\DataFixtures\Work\Member
 */
class GroupFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var string $name */
        foreach (['Our Staff', 'Customers'] as $name) {
            $manager->persist((new Group)->setName($name));
        }

        $manager->flush();
    }
}
