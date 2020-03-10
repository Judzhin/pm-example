<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity\Work;

use App\Entity\Work\Project\Department;
use App\Entity\Work\Project\Role;
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
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
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
     * @return $this
     */
    public function setSort(int $sort): self
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
    public function setDepartments($departments): self
    {
        $this->departments = $departments;
        return $this;
    }

    /**
     * @param array $departments
     * @return $this
     * @throws \Throwable
     */
    public function addDepartments(array $departments): self
    {
        /** @var Department $department */
        foreach ($departments as $department) {
            $this->addDepartment($department);
        }
        return $this;
    }

    /**
     * @param Department $department
     * @return $this
     * @throws \Throwable
     */
    public function addDepartment(Department $department): self
    {
        /** @var Department $current */
        foreach ($this->departments as $current) {
            if ($department->isEqualName($current)) {
                throw DomainException::departmentAlreadyExists();
            }
        }

        $this->departments->add($department->setProject($this));

        return $this;
    }

    /**
     * @param Department $department
     * @return $this
     * @throws \Throwable
     */
    public function editDepartment(Department $department): self
    {
        /**
         * @param Department $current
         * @param Department $department
         * @return bool
         */
        $isEqual = static function (Department $current, Department $department): bool {
            return $current->getId()->toString() === $department->getId()->toString();
        };

        /** @var Department $current */
        foreach ($this->departments as $current) {
            if ($isEqual($current, $department)) {
                $current->setName($department->getName());
                return $this;
            }
        }

        throw DomainException::departmentIsNotFound();
    }

    /**
     * @param array $departments
     * @return $this
     * @throws \Throwable
     */
    public function removeDepartments(array $departments): self
    {
        /** @var Department $department */
        foreach ($departments as $department) {
            $this->addDepartment($department);
        }
        return $this;
    }

    /**
     * @param Department $department
     * @return $this
     * @throws \Throwable
     */
    public function removeDepartment(Department $department): self
    {
        /** @var Department $current */
        foreach ($this->departments as $current) {
            if ($department->isEqualName($current)) {
                $this->departments->removeElement($current);
                return $this;
            }
        }

        throw DomainException::departmentIsNotFound();

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
     * @param UuidInterface $id
     * @return bool
     */
    public function hasMember(UuidInterface $id): bool
    {
        foreach ($this->memberships as $membership) {
            if ($membership->isForMember($id)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Member $member
     * @param Department[] $departments
     * @param Role[] $roles
     * @throws \Exception
     */
    public function addMember(Member $member, array $departments, array $roles): void
    {
        /** @var Membership $membership */
        foreach ($this->memberships as $membership) {
            if ($membership->isForMember($member)) {
                throw new \DomainException('Member already exists.');
            }
        }

        $this->memberships->add(new Membership(
            $this, $member, array_map([$this, 'getDepartment'], $departments), $roles
        ));
    }

//    /**
//     * @param MemberId $member
//     * @param DepartmentId[] $departmentIds
//     * @param Role[] $roles
//     */
//    public function editMember(MemberId $member, array $departmentIds, array $roles): void
//    {
//        foreach ($this->memberships as $membership) {
//            if ($membership->isForMember($member)) {
//                $membership->changeDepartments(array_map([$this, 'getDepartment'], $departmentIds));
//                $membership->changeRoles($roles);
//                return;
//            }
//        }
//        throw new \DomainException('Member is not found.');
//    }
//
//    public function removeMember(MemberId $member): void
//    {
//        foreach ($this->memberships as $membership) {
//            if ($membership->isForMember($member)) {
//                $this->memberships->removeElement($membership);
//                return;
//            }
//        }
//        throw new \DomainException('Member is not found.');
//    }
//
//    public function isMemberGranted(MemberId $id, string $permission): bool
//    {
//        foreach ($this->memberships as $membership) {
//            if ($membership->isForMember($id)) {
//                return $membership->isGranted($permission);
//            }
//        }
//        return false;
//    }

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

    /**
     *
     */
    public function archive(): void
    {
        if ($this->isArchived()) {
            throw new DomainException('Project is already archived.');
        }

        $this->setStatus(Status::archived());
    }

    /**
     *
     */
    public function reinstate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('Project is already active.');
        }
        $this->setStatus(Status::active());
    }


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