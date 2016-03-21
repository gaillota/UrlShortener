<?php

namespace AG\ShortenerBundle\Entity;

use AG\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Scan
 *
 * @ORM\Table(name="scan")
 * @ORM\Entity(repositoryClass="AG\ShortenerBundle\Repository\ScanRepository")
 */
class Scan
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var Link
     *
     * @ORM\ManyToOne(targetEntity="Link", inversedBy="scans")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid()
     *
     */
    private $link;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AG\UserBundle\Entity\User")
     * @Gedmo\Blameable(on="create")
     */
    private $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Scan
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set link
     *
     * @param Link $link
     * @return Scan
     */
    public function setLink(Link $link = null)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return Link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Scan
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
