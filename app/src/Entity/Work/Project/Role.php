<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity\Work\Project;

use App\Entity\Work\Project;
use App\Model\Work\Project\Permission;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Role
 * @package App\Entity\Work\Project
 *
 * @ORM\Entity
 * @ORM\Table(name="work_project_roles")
 */
class Role
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
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
     * @var ArrayCollection|Permission[]
     * @ORM\Column(type="string")
     */
    private $permissions;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->permissions = new ArrayCollection;
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
     * @return Role
     */
    public function setId(UuidInterface $id): Role
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
     * @return Role
     */
    public function setProject(Project $project): Role
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
     * @return Role
     */
    public function setName(string $name): Role
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Permission[]|ArrayCollection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param Permission[]|ArrayCollection $permissions
     * @return Role
     */
    public function setPermissions($permissions): Role
    {
        $this->permissions = $permissions;
        return $this;
    }

    /**
     * @param Permission $permission
     * @return bool
     */
    public function hasPermission(Permission $permission): bool
    {
        return $this->permissions->exists(static function (Permission $current) use ($permission) {
            return $permission->isValueEqual($current);
        });
    }

    /**
     * @param Department $name
     * @return bool
     */
    public function isEqual(Department $name): bool
    {
        return $this->name === $name->getName();
    }
}