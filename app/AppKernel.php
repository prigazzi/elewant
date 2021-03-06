<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    private $developmentEnvironments = ['dev', 'test'];

    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new \HWI\Bundle\OAuthBundle\HWIOAuthBundle(),

            new \Prooph\Bundle\EventStore\ProophEventStoreBundle(),
            new \Prooph\Bundle\ServiceBus\ProophServiceBusBundle(),

            new Http\HttplugBundle\HttplugBundle(),

            new Elewant\AppBundle\ElewantAppBundle(),
            new Elewant\UserBundle\ElewantUserBundle(),
        ];

        if (in_array($this->getEnvironment(), $this->developmentEnvironments, true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();

            $bundles[] = new Elewant\DevelopmentBundle\ElewantDevelopmentBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        if (in_array($this->getEnvironment(), $this->developmentEnvironments, true)) {
            return '/dev/shm/elewant/var/cache/' . $this->getEnvironment();
        }

        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        if (in_array($this->getEnvironment(), $this->developmentEnvironments, true)) {
            return '/dev/shm/elewant/var/logs';
        }

        return dirname(__DIR__) . '/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
