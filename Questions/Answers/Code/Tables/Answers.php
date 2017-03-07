<?php

namespace Surveys\Questions\Answers\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answers
 *
 * @ORM\Table(name="surveys_questions_answers", indexes={@ORM\Index(name="question_id_index", columns={"question_id"}), @ORM\Index(name="parameter_id_index", columns={"parameter_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Answers extends \Kazist\Table\BaseTable
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
     * @var integer
     *
     * @ORM\Column(name="question_id", type="integer", length=11, nullable=false)
     */
    protected $question_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="parameter_id", type="integer", length=11, nullable=true)
     */
    protected $parameter_id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    protected $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=false)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=false)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=false)
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
     * Set question_id
     *
     * @param integer $questionId
     * @return Answers
     */
    public function setQuestionId($questionId)
    {
        $this->question_id = $questionId;

        return $this;
    }

    /**
     * Get question_id
     *
     * @return integer 
     */
    public function getQuestionId()
    {
        return $this->question_id;
    }

    /**
     * Set parameter_id
     *
     * @param integer $parameterId
     * @return Answers
     */
    public function setParameterId($parameterId)
    {
        $this->parameter_id = $parameterId;

        return $this;
    }

    /**
     * Get parameter_id
     *
     * @return integer 
     */
    public function getParameterId()
    {
        return $this->parameter_id;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Answers
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
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
