<?php

namespace App\DataFixtures;

use App\Entity\EmbeddedToken;
use App\Entity\User;
use App\Model\User\Email;
use App\Service\PasswordEncoder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    /** @var PasswordEncoder */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     * @param PasswordEncoder $passwordEncoder
     */
    public function __construct(PasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        /** @var Email $email */
        $email = new Email('demo@example.com');
        if (!$manager->getRepository(User::class)->findOneByEmail($email)) {

            /** @var User $user */
            $user = User::signUpByEmail(
                new Email('demo@example.com'),
                $this->passwordEncoder->encodePassword('secret'),
                EmbeddedToken::create()
            );

            $user->confirm();
            $user->setRoles(['ROLE_ADMIN']);

            $manager->persist($user);
            $manager->flush();
        }


    }
}
