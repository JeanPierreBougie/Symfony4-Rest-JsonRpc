<?php
/**
 * NOTE DO YOUR OWN STUFF HERE
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobRepository")
 * @ORM\Table(name="POSTE")
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="P_NOPOSTE", type="string")
     */
    private $id;

}
