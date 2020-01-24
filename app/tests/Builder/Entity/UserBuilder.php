<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Tests\Builder\Entity;

use App\Entity\EmbeddedToken;
use App\Entity\Name;
use App\Entity\Network;
use App\Entity\User;
use App\Model\Email;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserBuilder
 * @package App\Tests\Builder\Entity
 */
class UserBuilder
{
    /** @var UuidInterface */
    private $id;

    /** @var Name */
    private $name;

    /** @var Email */
    private $email;

    /** @var string */
    private $password;

    /** @var EmbeddedToken */
    private $confirmToken;

    /** @var Network */
    private $network;

    /** @var \DateTimeImmutable */
    private $createdAt;

    /** @var bool  */
    private $confirmed = false;

    /**
     * UserBuilder constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->name = new Name('First', 'Last');
        $this->createdAt = new \DateTimeImmutable;
    }

    /**
     * @param Email|null $email
     * @param null $password
     * @param EmbeddedToken|null $confirmToken
     * @return $this
     * @throws \Throwable
     */
    public function viaEmail(Email $email = null, $password = null, EmbeddedToken $confirmToken = null): self
    {
        /** @var UserBuilder $cloner */
        $clone = clone $this;
        $clone->email = $email ?? new Email('test@example.com');
        $clone->password = $password ?? 'secret';
        $clone->confirmToken = $confirmToken ?? EmbeddedToken::create();

        return $clone;
    }

    /**
     * @param Network|null $network
     * @return $this
     */
    public function viaNetwork(Network $network = null): self
    {
        /** @var UserBuilder $clone */
        $clone = clone $this;
        $clone->network = $network ?? (new Network)
                ->setNetwork('fb')
                ->setIdentity('0001');

        return $clone;
    }

    /**
     * @return $this
     */
    public function activate(): self
    {
        /** @var UserBuilder $clone */
        $clone = clone $this;
        $clone->confirmed = true;
        return $clone;
    }

    /**
     * @return User
     * @throws \Throwable
     */
    public function build(): User
    {
        /** @var UserInterface|User $object */
        $object = null;

        if ($this->email) {
            $object = User::signUpByEmail(
                $this->name,
                $this->email,
                $this->password,
                $this->confirmToken
            );

            if ($this->confirmed) {
                $object->confirm();
            }

        }

        if ($this->network) {
            $object = User::signUpByNetwork(
                $this->name,
                $this->network
            );
        }

        if (!$object) {
            throw new \BadMethodCallException('Specify via method');
        }

        return $object;
    }

}