<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\DataFixtures\Work\Member;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Entity\Work\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class MemberFixtures
 * @package App\DataFixtures\Work\Member
 */
class MemberFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @inheritdoc
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {

        /** @var array $maps */
        $maps = [
            UserFixtures::REFERENCE_JUDZHIN => GroupFixtures::REFERENCE_STAFF,
            UserFixtures::REFERENCE_JOHN => GroupFixtures::REFERENCE_STAFF
        ];

        foreach ($maps as $userInfo => $groupInfo) {
            $manager->persist(self::create(
                $this->getReference(UserFixtures::REFERENCE_JUDZHIN),
                $this->getReference(GroupFixtures::REFERENCE_STAFF)
            ));
        }

        $manager->flush();
    }

    /**
     * @param User $user
     * @param Member\Group $group
     * @return Member
     */
    private static function create(User $user, Member\Group $group): Member
    {
        /** @var Member $member */
        return (new Member)
            ->setEmail($user->getEmail())
            ->setName($user->getName())
            ->setGroup($group);
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            GroupFixtures::class
        ];
    }
}
