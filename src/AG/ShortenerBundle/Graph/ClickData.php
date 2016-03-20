<?php

namespace AG\ShortenerBundle\Graph;

class ClickData
{
    /**
     * @var \DateTime
     */
    private $first;

    /**
     * @var \DateTime
     */
    private $last;

    /**
     * ClicksData constructor.
     * @param \DateTime $first
     * @param \DateTime $last
     */
    public function __construct()
    {
        $this->first = new \DateTime();
        $this->last = new \DateTime();
    }

    public function getGroupBy()
    {
        //TODO
    }


    public function getInterval()
    {
        //TODO
    }

    /**
     * @param $data
     * @return \DateTime
     */
    private function getDateTime($data)
    {
        return new \DateTime($data['year'].'-'.$data['month'].'-'.$data['day']);
    }

    /**
     * @return \DateTime
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @param \DateTime $first
     */
    public function setFirst($first)
    {
        $this->first = $first;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @param \DateTime $last
     */
    public function setLast($last)
    {
        $this->last = $last;
        return $this;
    }
}