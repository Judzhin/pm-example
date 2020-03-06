<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity\Work\Project;

use App\Entity\Work\Project;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Department
 * @package App\Entity\Work\Project
 *
 * @ORM\Entity
 * @ORM\Table(name="work_project_departments")
 */
class Department
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Work\Project", inversedBy="departments")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var
     */
    private $members;

    /**
     * Department constructor.
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
     * @return Department
     */
    public function setId(UuidInterface $id): Department
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     * @return Department
     */
    public function setProject(Project $project): Department
    {
        $this->project = $project;
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
     * @return Department
     */
    public function setName(string $name): Department
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param $members
     * @return $this
     */
    public function setMembers($members): self
    {
        $this->members = $members;
        return $this;
    }

    /**
     * @param Department $department
     * @return bool
     */
    public function isEqualName(Department $department): bool
    {
        return $this->getName() === $department->getName();
    }

    /**
     * @param Department $department
     * @return bool
     */
    public function isEqual(Department $department): bool
    {
        return $this->getId()->toString() === $department->getId()->toString();
        // return $this->getId()->equals($department->getId());
    }

}