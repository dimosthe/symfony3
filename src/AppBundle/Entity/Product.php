<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @UniqueEntity("name")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"full"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     * @Assert\Length(max=10)
     * @Groups({"full", "limited"})
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     * @Assert\Type("numeric")
     * @Groups({"full", "limited"})
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Groups({"full", "limited"})
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isValid", type="boolean")
     * @Groups({"full", "limited"})
     */
    private $isValid = false;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

     /**
     * Set isValid
     *
     * @param boolean $price
     *
     * @return Product
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return float
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    public function fromArray(array $userInput, $allowedFields = array())
    {
        foreach ($userInput as $key => $value)
        {
            if (in_array($key, $allowedFields))
            {
                $this->$key = $value;
            }
        }
    }
}

