<?php

namespace AppBundle\Entity;

/**
 * qcm_entries
 */
class qcm_entries
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
     * @var string
     */
    private $awnser;

    /**
     * @var bool
     */
    private $correct;


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
     * @return qcm_entries
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
     * Set awnser
     *
     * @param string $awnser
     *
     * @return qcm_entries
     */
    public function setAwnser($awnser)
    {
        $this->awnser = $awnser;

        return $this;
    }

    /**
     * Get awnser
     *
     * @return string
     */
    public function getAwnser()
    {
        return $this->awnser;
    }

    /**
     * @return boolean
     */
    public function isCorrect()
    {
        return $this->correct;
    }

    /**
     * @param boolean $correct
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;
    }


}

