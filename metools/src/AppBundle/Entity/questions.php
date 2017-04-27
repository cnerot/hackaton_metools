<?php

namespace AppBundle\Entity;

/**
 * questions
 */
class questions
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $type;

    /**
     * @var bool
     */
    private $qcm;

    /**
     * @var string
     */
    private $content;
    /**
     * @var int
     */
    private $validline;
    /**
     * @var string
     */
    private $code;


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
     * Set type
     *
     * @param integer $type
     *
     * @return questions
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set qcm
     *
     * @param boolean $qcm
     *
     * @return questions
     */
    public function setQcm($qcm)
    {
        $this->qcm = $qcm;

        return $this;
    }

    /**
     * Get qcm
     *
     * @return bool
     */
    public function getQcm()
    {
        return $this->qcm;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return questions
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getValidline()
    {
        return $this->validline;
    }

    /**
     * @param int $validline
     */
    public function setValidline($validline)
    {
        $this->validline = $validline;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

}

