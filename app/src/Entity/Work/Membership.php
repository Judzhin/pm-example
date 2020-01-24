<?php

declare(strict_types=1);

namespace App\Entity\Work;

use App\Entity\Work\Project\Department;
use App\Entity\Work\Project\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Membership
 * @package App\Entity\Work
 *
 * @ORM\Entity
 * @ORM\Table(name="work_project_memberships", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"project_id", "member_id"})
 * })
 */
class Membership
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
     * @var Project
     * @ORM\ManyToOne(targetEntity="App\Entity\Work\Project", inversedBy="memberships")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;

    /**
     * @var Member
     * @ORM\ManyToOne(targetEntity="App\Entity\Work\Member")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id", nullable=false)
     */
    private $member;

    /**
     * @var ArrayCollection|Department[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Work\Project\Department")
     * @ORM\JoinTable(name="work_memberships_departments",
     *     joinColumns={@ORM\JoinColumn(name="membership_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="department_id", referencedColumnName="id")}
     * )
     */
    private $departments;

    /**
     * @var ArrayCollection|Role[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Work\Project\Role")
     * @ORM\JoinTable(name="work_memberships_roles",
     *     joinColumns={@ORM\JoinColumn(name="membership_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $roles;

    /**
     * Membership constructor.
     */
    public function __construct()
    {
        $this->departments = new ArrayCollection;
        $this->roles = new ArrayCollection;
    }

    /**
     * @param Department[] $departments
     */
    public function changeDepartments(array $departments): void
    {
        $this->guardDepartments($departments);

        $current = $this->departments->toArray();
        $new = $departments;

        $compare = static function (Department $a, Department $b): int {
            return $a->getId()->getValue() <=> $b->getId()->getValue();
        };

        foreach (array_udiff($current, $new, $compare) as $item) {
            $this->departments->removeElement($item);
        }

        foreach (array_udiff($new, $current, $compare) as $item) {
            $this->departments->add($item);
        }
    }

    /**
     * @param Role[] $roles
     */
    public function changeRoles(array $roles): void
    {
        $this->guardRoles($roles);

        $current = $this->roles->toArray();
        $new = $roles;

        $compare = static function (Role $a, Role $b): int {
            return $a->getId()->getValue() <=> $b->getId()->getValue();
        };

        foreach (array_udiff($current, $new, $compare) as $item) {
            $this->roles->removeElement($item);
        }

        foreach (array_udiff($new, $current, $compare) as $item) {
            $this->roles->add($item);
        }
    }

    /**
     * @param Member $member
     * @return bool
     */
    public function isForMember(Member $member): bool
    {
        return $this->member->getId()->isEqual($member->getId()->toString());
    }

    /**
     * @param Department $department
     * @return bool
     */
    public function isForDepartment(Department $department): bool
    {
        foreach ($this->departments as $item) {
            if ($item->getId()->isEqual($department->getId()->toString())) {
                return true;
            }
        }
        return false;
    }

    public function isGranted(string $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Member
     */
    public function getMember(): Member
    {
        return $this->member;
    }

    /**
     * @return Role[]
     */
    public function getRoles(): array
    {
        return $this->roles->toArray();
    }

    /**
     * @return Department[]
     */
    public function getDepartments(): array
    {
        return $this->departments->toArray();
    }

    /**
     * @param array $departments
     */
    public function guardDepartments(array $departments): void
    {
        if (\count($departments) === 0) {
            throw new \DomainException('Set at least one department.');
        }
    }

    /**
     * @param array $roles
     */
    public function guardRoles(array $roles): void
    {
        if (\count($roles) === 0) {
            throw new \DomainException('Set at least one role.');
        }
    }
}