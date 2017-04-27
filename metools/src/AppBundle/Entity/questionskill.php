<?php

namespace AppBundle\Entity;

/**
 * questionskill
 */
class questionskill
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $questionId;

    /**
     * @var int
     */
    private $skillId;


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
     * Set questionId
     *
     * @param integer $questionId
     *
     * @return questionskill
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;

        return $this;
    }

    /**
     * Get questionId
     *
     * @return int
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * Set skillId
     *
     * @param integer $skillId
     *
     * @return questionskill
     */
    public function setSkillId($skillId)
    {
        $this->skillId = $skillId;

        return $this;
    }

    /**
     * Get skillId
     *
     * @return int
     */
    public function getSkillId()
    {
        return $this->skillId;
    }
}

