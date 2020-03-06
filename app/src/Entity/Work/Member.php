<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Entity\Work;

use App\Entity\Name;
use App\Entity\Work\Member\Group;
use App\Exception\DomainException;
use App\Model\Email;
use App\Model\Work\Status;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Member
 * @package App\Entity\Work
 *
 * @ORM\Entity()
 * @ORM\Table(name="work_members")
 */
class Member implements StatusAwareInterface
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Work\Member\Group", inversedBy="members")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     */
    private $group;

    /**
     * @var Name
     * @ORM\Embedded(class="App\Entity\Name")
     */
    private $name;

    /**
     * @var Email
     * @ORM\Column(type="email")
     */
    private $email;

    use StatusAwareTrait;

    /**
     * Member constructor.
     */
    public function __construct()
    {
        $this->setStatus(Status::default());
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     * @return Member
     */
    public function setId(UuidInterface $id): Member
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     * @return Member
     */
    public function setGroup(Group $group): Member
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @param Name $name
     * @return Member
     */
    public function setName(Name $name): Member
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @param Email $email
     * @return Member
     */
    public function setEmail(Email $email): Member
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return Member
     * @throws \Throwable
     */
    public function archive(): Member
    {
        if ($this->isArchived()) {
            throw DomainException::memberIsAlreadyArchived();
        }

        $this->setStatus(Status::archived());
        return $this;
    }

    /**
     * @return Member
     * @throws \Throwable
     */
    public function reinstate(): Member
    {
        if ($this->isActive()) {
            throw DomainException::memberIsAlreadyActive();
        }

        $this->setStatus(Status::active());
        return $this;
    }

    /**
     * @param Member $member
     * @return bool
     */
    public function isEqual(self $member): bool
    {
        return $this->getId()->toString() === $member->getId()->toString();
        // return $this->getId()->equals($member->getId());
    }
}