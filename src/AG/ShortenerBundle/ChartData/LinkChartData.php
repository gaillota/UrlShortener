<?php

namespace AG\ShortenerBundle\ChartData;

use AG\ShortenerBundle\Entity\Link;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

class LinkChartData
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Link
     */
    private $link;

    /**
     * @var  null|\DateTime
     */
    private $chartStart;

    /**
     * @var null|\DateTime
     */
    private $chartEnd;

    private $clicks =  null;

    private $scans = null;

    /**
     * ClicksData constructor.
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->chartStart = new \DateTime();
        $this->chartEnd = new \DateTime();
    }

    public function computeData()
    {
        $this->computeClicks();
        $this->computeScans();
    }

    /**
     * @param Link $link
     */
    public function setLink(Link $link)
    {
        $this->link = $link;
    }

    /**
     * @return \DateTime
     */
    public function getChartStart()
    {
        return $this->chartStart;
    }

    public function setClicks()
    {
        if (!$this->link)
            return;

        $clicks = $this->em->getRepository('AGShortenerBundle:Click')->getClicks($this->link);
        $this->clicks = $clicks;

        if (empty($clicks))
            return;

        $this->computeDuration($clicks);
    }

    public function getClicks()
    {
        return implode(',', $this->clicks);
    }

    public function setScans()
    {
        if (!$this->link)
            return;

        $scans = $this->em->getRepository('AGShortenerBundle:Scan')->getScans($this->link);
        $this->scans = $scans;

        if (empty($scans))
            return;

        $this->computeDuration($scans);
    }

    public function getScans()
    {
        return implode(',', $this->scans);
    }

    /**
     * Helpers
     */

    /**
     * @param $data
     */
    private function computeDuration($data)
    {
        $first = $this->getDateTime($data[0]);
        $last = $this->getDateTime(end($data));

        $this->chartStart = min($this->chartStart, $first);
        $this->chartEnd = max($this->chartEnd, $last);
    }

    /**
     * Compute clicks data and fill array accordingly
     */
    private function computeClicks()
    {
        // Get length of chart
        $length = $this->chartEnd->diff($this->chartStart)->d;



        // Store clicks
        $clicksData = $this->clicks;
        // Create an array filled with 0 (+1 for if no clicks today)
        $clicks = array_fill(0, $length + 1, 0);

        foreach ($clicksData as $click) {
            // Set the number of clicks at the appropriate index
            $clicks[$this->chartStart->diff($this->getDateTime($click), true)->d] = intval($click['clickCount']);
        }

        $this->clicks = $clicks;
    }

    /**
     * Compute scans data and fill array accordingly
     */
    private function computeScans()
    {
        // Get length of chart
        $length = $this->chartEnd->diff($this->chartStart)->d;

        $scansData = $this->scans;
        $scans = array_fill(0, $length + 1, 0);

        foreach ($scansData as $scan) {
            $scans[$this->chartStart->diff($this->getDateTime($scan), true)->d] = intval($scan['scanCount']);
        }

        $this->scans = $scans;
    }

    /**
     * @param \DateTime $date1
     * @param \DateTime $date2
     * @return int
     */
    private function getInterval(\DateTime $date1, \DateTime $date2)
    {
        $diff = abs($date1->getTimestamp() - $date2->getTimestamp());
        return intval($diff / 3600 / 24);
    }

    /**
     * @param $data
     * @return \DateTime
     */
    private function getDateTime($data)
    {
        return new \DateTime($data['year'] . '-' . $data['month'] . '-' . $data['day']);
    }
}