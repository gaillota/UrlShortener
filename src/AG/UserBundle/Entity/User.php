<?php

namespace AG\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AG\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
*/
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="foreground_color", type="string", length=8)
     * @Assert\Regex(
     *     pattern="/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/",
     *     message="La couleur donnée ne correspond pas à une couleur hexadécimale"
     * )
     */
    private $foregroundColor = "#ffffff";

    /**
     * @var string
     *
     * @ORM\Column(name="background_color", type="string", length=8)
     * @Assert\Regex(
     *     pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$",
     *     message="La couleur donnée ne correspond pas à une couleur hexadécimale"
     * )
     */
    private $backgroundColor = "#000000";

    /**
     * var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AG\ShortenerBundle\Entity\Link", mappedBy="owner")
     */
    private $links;


    public function __construct()
    {
        parent::__construct();
        $this->links = new ArrayCollection();
    }

    /**
     * Add links
     *
     * @param \AG\ShortenerBundle\Entity\Link $links
     * @return User
     */
    public function addLink(\AG\ShortenerBundle\Entity\Link $links)
    {
        $this->links[] = $links;

        return $this;
    }

    /**
     * Remove links
     *
     * @param \AG\ShortenerBundle\Entity\Link $links
     */
    public function removeLink(\AG\ShortenerBundle\Entity\Link $links)
    {
        $this->links->removeElement($links);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set foregroundColor
     *
     * @param string $foregroundColor
     * @return User
     */
    public function setForegroundColor($foregroundColor)
    {
        $this->foregroundColor = $foregroundColor;

        return $this;
    }

    /**
     * Get foregroundColor
     *
     * @return string 
     */
    public function getForegroundColor()
    {
        return $this->foregroundColor;
    }

    /**
     * Set backgroundColor
     *
     * @param string $backgroundColor
     * @return User
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * Get backgroundColor
     *
     * @return string 
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }
}
