<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(), //@Template

            // FOS
            new FOS\UserBundle\FOSUserBundle(),

            // JMS
            new JMS\AopBundle\JMSAopBundle(), // Needed for DiExtra
            new JMS\DiExtraBundle\JMSDiExtraBundle($this), //$request injection
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(), //@Secure && hasRole('ROLE')

            // Doctrine
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(), //@Blameable

            // KNP
            new \Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),

            // Endroid QR Code
            new Endroid\Bundle\QrCodeBundle\EndroidQrCodeBundle(),

            // Maxmind GeoIP
            new Maxmind\Bundle\GeoipBundle\MaxmindGeoipBundle(),

            // Application
            new AG\ShortenerBundle\AGShortenerBundle(),
            new AG\UserBundle\AGUserBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function __construct($environment, $debug)
    {
        date_default_timezone_set('Europe/Paris');
        parent::__construct($environment, $debug);
    }
}
