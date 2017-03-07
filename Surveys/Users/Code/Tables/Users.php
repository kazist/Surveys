<?php

namespace Surveys\Surveys\Users\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="surveys_surveys_users", indexes={@ORM\Index(name="survey_id_index", columns={"survey_id"}), @ORM\Index(name="user_id_index", columns={"user_id"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Users extends \Kazist\Table\BaseTable
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
     * @ORM\Column(name="survey_id", type="integer", length=11, nullable=false)
     */
    protected $survey_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", length=11, nullable=true)
     */
    protected $user_id;

    /**
     * @var string
     *
     * @ORM\Column(name="guest_id", type="string", length=255, nullable=true)
     */
    protected $guest_id;

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
     * Set survey_id
     *
     * @param integer $surveyId
     * @return Users
     */
    public function setSurveyId($surveyId)
    {
        $this->survey_id = $surveyId;

        return $this;
    }

    /**
     * Get survey_id
     *
     * @return integer 
     */
    public function getSurveyId()
    {
        return $this->survey_id;
    }

    /**
     * Set user_id
     *
     * @param integer $userId
     * @return Users
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set guest_id
     *
     * @param string $guestId
     * @return Users
     */
    public function setGuestId($guestId)
    {
        $this->guest_id = $guestId;

        return $this;
    }

    /**
     * Get guest_id
     *
     * @return string 
     */
    public function getGuestId()
    {
        return $this->guest_id;
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
