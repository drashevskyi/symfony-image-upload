<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImagesOld
 *
 * @ORM\Table(name="_images_old")
 * @ORM\Entity
 */
class ImagesOld
{
    /**
     * @var string
     *
     * @ORM\Column(name="NAME", type="text", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="SERVERNAME", type="text", nullable=false)
     */
    private $servername;

    /**
     * @var integer
     *
     * @ORM\Column(name="USER", type="integer", nullable=false)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="TIMESTAMP", type="datetime", nullable=false)
     */
    private $timestamp;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


}

