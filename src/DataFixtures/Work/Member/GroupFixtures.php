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
    /** @const REFERENCE_STAFF */
    public const REFERENCE_STAFF = 'group_staff';

    /** @const REFERENCE_CUSTOMER */
    public const REFERENCE_CUSTOMER = 'group_customer';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        // /** @var string $name */
        // foreach (['Our Staff', 'Customers'] as $name) {
        //     $manager->persist((new Group)->setName($name));
        // }

        /** @var Group $staff */
        $staff = $manager->persist((new Group)->setName('Our Staff'));
        $manager->persist($staff);
        $this->setReference(self::REFERENCE_STAFF, $staff);

        /** @var Group $customer */
        $customer = $manager->persist((new Group)->setName('Customers'));
        $manager->persist($customer);
        $this->setReference(self::REFERENCE_CUSTOMER, $customer);

        $manager->flush();
    }
}
