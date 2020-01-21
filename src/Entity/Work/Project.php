<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity\Work;

use App\Entity\Work\Project\Department;
use App\Exception\DomainException;
use App\Model\Work\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Project
 * @package App\Entity\Work
 *
 * @ORM\Entity
 * @ORM\Table(name="work_projects")
 */
class Project implements StatusAwareInterface
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $sort;

    use StatusAwareTrait;

    /**
     * @var ArrayCollection|Department[]
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Work\Project\Department",
     *     mappedBy="project", orphanRemoval=true, cascade={"all"}
     * )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $departments;

    /**
     * @var ArrayCollection|Membership[]
     * @ORM\OneToMany(targetEntity="Membership", mappedBy="project", orphanRemoval=true, cascade={"all"})
     */
    private $memberships;

    /**
     * @ORM\Version()
     * @ORM\Column(type="integer")
     */
    private $version;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->setStatus(Status::active());
        $this->departments = new ArrayCollection;
        $this->memberships = new ArrayCollection;
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
     * @return Project
     */
    public function setId(UuidInterface $id): Project
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
     * @return Project
     */
    public function setName(string $name): Project
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return Project
     */
    public function setSort(int $sort): Project
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return Department[]|ArrayCollection
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * @param Department[]|ArrayCollection $departments
     * @return Project
     */
    public function setDepartments($departments)
    {
        $this->departments = $departments;
        return $this;
    }

    /**
     * @return Membership[]|ArrayCollection
     */
    public function getMemberships()
    {
        return $this->memberships;
    }

    /**
     * @param Membership[]|ArrayCollection $memberships
     * @return Project
     */
    public function setMemberships($memberships)
    {
        $this->memberships = $memberships;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     * @return Project
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    //
    // // public function edit(string $name, int $sort): void
    // // {
    // //     $this->name = $name;
    // //     $this->sort = $sort;
    // // }
    //

     public function archive(): void
     {
         if ($this->isArchived()) {
             throw new DomainException('Project is already archived.');
         }
         $this->setStatus(Status::archived());
     }

     public function reinstate(): void
     {
         if ($this->isActive()) {
             throw new DomainException('Project is already active.');
         }
         $this->setStatus(Status::active());
     }

    //
    // public function addDepartment(DepartmentId $id, string $name): void
    // {
    //     foreach ($this->departments as $department) {
    //         if ($department->isNameEqual($name)) {
    //             throw new \DomainException('Department already exists.');
    //         }
    //     }
    //     $this->departments->add(new Department($this, $id, $name));
    // }
    //
    // public function editDepartment(DepartmentId $id, string $name): void
    // {
    //     foreach ($this->departments as $current) {
    //         if ($current->getId()->isEqual($id)) {
    //             $current->edit($name);
    //             return;
    //         }
    //     }
    //     throw new \DomainException('Department is not found.');
    // }
    //
    // public function removeDepartment(DepartmentId $id): void
    // {
    //     foreach ($this->departments as $department) {
    //         if ($department->getId()->isEqual($id)) {
    //             foreach ($this->memberships as $membership) {
    //                 if ($membership->isForDepartment($id)) {
    //                     throw new \DomainException('Unable to remove department with members.');
    //                 }
    //             }
    //             $this->departments->removeElement($department);
    //             return;
    //         }
    //     }
    //     throw new \DomainException('Department is not found.');
    // }
    //
    // public function hasMember(MemberId $id): bool
    // {
    //     foreach ($this->memberships as $membership) {
    //         if ($membership->isForMember($id)) {
    //             return true;
    //         }
    //     }
    //     return false;
    // }
    //
    // /**
    //  * @param Member $member
    //  * @param DepartmentId[] $departmentIds
    //  * @param Role[] $roles
    //  * @throws \Exception
    //  */
    // public function addMember(Member $member, array $departmentIds, array $roles): void
    // {
    //     foreach ($this->memberships as $membership) {
    //         if ($membership->isForMember($member->getId())) {
    //             throw new \DomainException('Member already exists.');
    //         }
    //     }
    //     $departments = array_map([$this, 'getDepartment'], $departmentIds);
    //     $this->memberships->add(new Membership($this, $member, $departments, $roles));
    // }
    //
    ///**
    // * @param MemberId $member
    // * @param DepartmentId[] $departmentIds
    // * @param Role[] $roles
    // */
    //public function editMember(MemberId $member, array $departmentIds, array $roles): void
    //{
    //    foreach ($this->memberships as $membership) {
    //        if ($membership->isForMember($member)) {
    //            $membership->changeDepartments(array_map([$this, 'getDepartment'], $departmentIds));
    //            $membership->changeRoles($roles);
    //            return;
    //        }
    //    }
    //    throw new \DomainException('Member is not found.');
    //}
    //
    //public function removeMember(MemberId $member): void
    //{
    //    foreach ($this->memberships as $membership) {
    //        if ($membership->isForMember($member)) {
    //            $this->memberships->removeElement($membership);
    //            return;
    //        }
    //    }
    //    throw new \DomainException('Member is not found.');
    //}
    //
    //public function isMemberGranted(MemberId $id, string $permission): bool
    //{
    //    foreach ($this->memberships as $membership) {
    //        if ($membership->isForMember($id)) {
    //            return $membership->isGranted($permission);
    //        }
    //    }
    //    return false;
    //}
    //
    //public function isArchived(): bool
    //{
    //    return $this->status->isArchived();
    //}
    //
    //public function isActive(): bool
    //{
    //    return $this->status->isActive();
    //}
    //
    //public function getId(): Id
    //{
    //    return $this->id;
    //}
    //
    //public function getName(): string
    //{
    //    return $this->name;
    //}
    //
    //public function getSort(): int
    //{
    //    return $this->sort;
    //}
    //
    //public function getStatus(): Status
    //{
    //    return $this->status;
    //}
    //
    //public function getDepartments()
    //{
    //    return $this->departments->toArray();
    //}
    //
    //public function getDepartment(DepartmentId $id): Department
    //{
    //    foreach ($this->departments as $department) {
    //        if ($department->getId()->isEqual($id)) {
    //            return $department;
    //        }
    //    }
    //    throw new \DomainException('Department is not found.');
    //}
    //
    //public function getMemberships()
    //{
    //    return $this->memberships->toArray();
    //}
    //
    //public function getMembership(MemberId $id): Membership
    //{
    //    foreach ($this->memberships as $membership) {
    //        if ($membership->isForMember($id)) {
    //            return $membership;
    //        }
    //    }
    //    throw new \DomainException('Member is not found.');
    //}
}