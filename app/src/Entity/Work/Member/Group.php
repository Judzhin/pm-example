<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Entity\Work\Member;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Group
 * @package App\Entity\Work\Member
 *
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @ORM\Table(name="work_member_groups")
 */
class Group
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\GeneratedValue("UUID")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Work\Member",
     *     mappedBy="group",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    private $members;

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->members = new ArrayCollection;
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
     * @return Group
     */
    public function setId(UuidInterface $id): Group
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Group
     */
    public function setName(string $name): Group
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param ArrayCollection $members
     * @return Group
     */
    public function setMembers(ArrayCollection $members): Group
    {
        $this->members = $members;
        return $this;
    }
}