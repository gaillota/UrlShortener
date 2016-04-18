<?php

namespace AG\ShortenerBundle\Twig;


class DateFormatter extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('date_format', array(
                $this,
                'dateFormatter'
            ))
        );
    }

    public function dateFormatter(\DateTime $date)
    {
        $now = new \DateTime();

        if ($date->format('Y-m-d') == $now->format('Y-m-d')) {
            return $date->format('H\hm');
        } else if ($date->format('Y') == $now->format('Y')) {
            return $date->format('d/m');
        } else {
            return $date->format('d/m/Y');
        }
    }

    public function getName()
    {
        return 'date_format_extension';
    }
}