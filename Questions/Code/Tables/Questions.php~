<?php

namespace Surveys\Questions\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Questions
 *
 * @ORM\Table(name="surveys_questions", indexes={@ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Questions extends \Kazist\Table\BaseTable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=255, nullable=false)
     */
    protected $question;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="chart", type="string", length=255, nullable=true)
     */
    protected $chart;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordering", type="integer", length=11, nullable=true)
     */
    protected $ordering;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=true)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=true)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=true)
     */
    protected $date_modified;


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
     * Set question
     *
     * @param string $question
     * @return Questions
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Questions
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set chart
     *
     * @param string $chart
     * @return Questions
     */
    public function setChart($chart)
    {
        $this->chart = $chart;

        return $this;
    }

    /**
     * Get chart
     *
     * @return string 
     */
    public function getChart()
    {
        return $this->chart;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     * @return Questions
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Get created_by
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Get modified_by
     *
     * @return integer 
     */
    public function getModifiedBy()
    {
        return $this->modified_by;
    }

    /**
     * Get date_modified
     *
     * @return \DateTime 
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        // Add your code here
    }
}
