<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Tests\Builder\Entity;

use App\Entity\EmbeddedToken;
use App\Entity\Network;
use App\Entity\User;
use App\Model\User\Email;
use http\Exception\BadMethodCallException;
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
        $this->createdAt = new \DateTimeImmutable;
    }

    /**
     * @param Email|null $email
     * @param null $password
     * @param EmbeddedToken|null $confirmToken
     * @return UserBuilder
     * @throws \Exception
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
     * @return UserBuilder
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
     * @return UserBuilder
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
     * @throws \Exception
     */
    public function build(): User
    {
        /** @var UserInterface|User $object */
        $object = null;

        if ($this->email) {
            $object = User::signUpByEmail(
                $this->email,
                $this->password,
                $this->confirmToken
            );

            if ($this->confirmed) {
                $object->confirmSignUp();
            }

        }

        if ($this->network) {
            $object = User::signUpByNetwork($this->network);
        }

        if (!$object) {
            throw new \BadMethodCallException('Specify via method');
        }

        return $object;
    }

}