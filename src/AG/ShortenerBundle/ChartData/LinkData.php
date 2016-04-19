<?php

namespace AG\ShortenerBundle\ChartData;

use AG\ShortenerBundle\Entity\Link;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

class LinkData
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
     * @var \DateTime
     */
    private $chartStart;

    /**
     * @var \DateTime
     */
    private $chartEnd;

    private $clicks = array();
    
    private $browsers = array();

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
        $this->computeBrowsers();
    }

    /**
     * Link on which analytics will be compute
     * 
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

    public function getClicks()
    {
        return implode(',', $this->clicks);
    }

    public function getBrowsers()
    {
        return json_encode($this->browsers, JSON_NUMERIC_CHECK);
    }

    /**
     * Computation functions
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
        if (!$this->link)
            return;

        $clicksData = $this->em->getRepository('AGShortenerBundle:Click')->getClicks($this->link);

        if (empty($clicksData))
            return;

        $this->computeDuration($clicksData);
        
        // Get length of chart
        $length = $this->chartEnd->diff($this->chartStart)->d;

        // Create an array filled with 0 (+1 for if no clicks until now)
        $clicks = array_fill(0, $length + 1, 0);

        foreach ($clicksData as $click) {
            // Set the number of clicks at the appropriate index
            $clicks[$this->chartStart->diff($this->getDateTime($click), true)->d] = intval($click['clickCount']);
        }

        // Add a value of 0 before and after the only date if needed
        if ($length == 0) {
            $this->chartStart->sub(new \DateInterval('P1D'));
            array_unshift($clicks, 0);
            array_push($clicks, 0);
        }

        $this->clicks = $clicks;
    }

    private function computeBrowsers()
    {
        if (!$this->link)
            return;
        
        $browsersData = $this->em->getRepository('AGShortenerBundle:Click')->getBrowsers($this->link);
        
        if (empty($browsersData))
            return;

        $browsers = array();

        foreach ($browsersData as $browser) {
            $browsers[] = array(
                'name' => $browser[0]['browser'],
                'y' => $browser['browserCount']
            );
        }

        $this->browsers = $browsers;
    }

    /**
     * Helpers
     */

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