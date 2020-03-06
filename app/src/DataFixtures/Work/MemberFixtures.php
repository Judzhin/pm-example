<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\DataFixtures\Work;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Entity\Work\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class MemberFixtures
 * @package App\DataFixtures\Work
 */
class MemberFixtures extends Fixture implements DependentFixtureInterface
{
    /** @const REFERENCE_JUDZHIN */
    public const REFERENCE_JUDZHIN = UserFixtures::REFERENCE_JUDZHIN;

    /** @const REFERENCE_JOHN */
    public const REFERENCE_JOHN = UserFixtures::REFERENCE_JOHN;

    /**
     * @inheritDoc
     *
     * @param ObjectManager $manager
     * @throws \Throwable
     */
    public function load(ObjectManager $manager): void
    {
        /** @var array $maps */
        $maps = [
            UserFixtures::REFERENCE_JUDZHIN => MemberGroupFixtures::REFERENCE_STAFF,
            UserFixtures::REFERENCE_JOHN => MemberGroupFixtures::REFERENCE_STAFF
        ];

        /**
         * @var string $userInfo
         * @var string $groupInfo
         */
        foreach ($maps as $userInfo => $groupInfo) {
            $manager->persist($member = self::create(
                $this->getReference($userInfo),
                $this->getReference($groupInfo)
            ));

            $this->setReference($userInfo, $member);
        }

        $manager->flush();
    }

    /**
     * @param User $user
     * @param Member\Group $group
     * @return Member
     * @throws \Throwable
     */
    private static function create(User $user, Member\Group $group): Member
    {
        /** @var Member $member */
        return (new Member)
            ->setId($user->getId())
            ->setEmail($user->getEmail())
            ->setName($user->getName())
            ->setGroup($group)
            ->reinstate();
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
            MemberGroupFixtures::class
        ];
    }
}
