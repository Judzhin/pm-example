<?php

namespace App\DataFixtures;

use App\Entity\EmbeddedToken;
use App\Entity\Name;
use App\Entity\User;
use App\Model\Email;
use App\Service\PasswordEncoder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    /** @const REFERENCE_JUDZHIN */
    public const REFERENCE_JUDZHIN = 'user_judzhin';

    /** @const REFERENCE_JUDZHIN  */
    public const REFERENCE_JOHN = 'user_john';

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
     * @throws \Throwable
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $judzhin */
        $judzhin = User::signUpByEmail(
            new Name('Judzhin', 'Miles'),
            new Email('demo@example.com'),
            $this->passwordEncoder->encodePassword('secret'),
            EmbeddedToken::create()
        );

        $judzhin->confirm();
        $judzhin->setRoles(['ROLE_ADMIN']);

        $manager->persist($judzhin);
        $this->setReference(self::REFERENCE_JUDZHIN, $judzhin);

        /** @var User $john */
        $john = User::signUpByEmail(
            new Name('John', 'Doe'),
            new Email('john.doe@example.com'),
            $hash = $this->passwordEncoder->encodePassword('password'),
            EmbeddedToken::create()
        );

        $john->confirm();
        $john->setRoles(['ROLE_USER']);

        $manager->persist($john);
        $this->setReference(self::REFERENCE_JOHN, $john);

        // Flush data
        $manager->flush();

    }
}
