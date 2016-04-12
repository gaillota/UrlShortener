<?php


namespace AG\ShortenerBundle\Twig;


class HexToRgbTransformer extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('hex_to_rgb', array(
                $this,
                'colorTransformer'
            ))
        );
    }

    public function colorTransformer($hexColor)
    {
        $hex = str_replace("#", "", $hexColor);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);

        return $rgb;
    }

    public function getName()
    {
        return 'hex_to_rgb_extension';
    }
}