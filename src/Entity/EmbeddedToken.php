<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Math\Rand;

/**
 * Class EmbeddedToken
 * @package App\Entity
 *
 * @ORM\Embeddable
 */
class EmbeddedToken
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, name="value")
     */
    private $value = null;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $expires = null;

    /**
     * ConfirmToken constructor.
     * @param string $value
     * @param \DateTimeImmutable $expires
     */
    public function __construct(string $value, \DateTimeImmutable $expires)
    {
        $this->value = $value;
        $this->expires = $expires;
    }

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return EmbeddedToken
     */
    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getExpires(): ?\DateTimeImmutable
    {
        return $this->expires;
    }

    /**
     * @param \DateTimeImmutable $expires
     * @return EmbeddedToken
     */
    public function setExpires(\DateTimeImmutable $expires): self
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * @param \DateInterval|null $interval
     * @return EmbeddedToken
     * @throws \Exception
     */
    public static function create(\DateInterval $interval = null): self
    {
        return new self(
            Rand::getString(10),
            (new \DateTimeImmutable)->add($interval ?? new \DateInterval("P3D"))
        );
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->value);
    }
}
