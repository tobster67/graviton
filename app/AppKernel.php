<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

    /**
     * @return array of $bundles
     */
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new AppBundle\AppBundle(),
        );
        // intialize GravitonCoreBundle with all dependend Bundles
        $GravitonCoreBundle = new Graviton\CoreBundle\GravitonCoreBundle();
        $bundles[] = $GravitonCoreBundle;
        $bundles = array_merge( $bundles, $GravitonCoreBundle->getBundles() );

        if (in_array($this->getEnvironment(), array('dev', 'test', 'oauth_dev'))) {
            $bundles[] = new \Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new \Graviton\TestBundle\GravitonTestBundle();
        }

        if (strpos($this->getEnvironment(), 'oauth') !== false) {
            $bundles[] = new \HWI\Bundle\OAuthBundle\HWIOAuthBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * dont rebuild container with debug over and over again during tests
     *
     * This is very much what is described in http://kriswallsmith.net/post/27979797907
     *
     * @return void
     */
    protected function initializeContainer()
    {
        static $first = true;

        if ('test' !== $this->getEnvironment()) {
            parent::initializeContainer();
            return;
        }

        $debug = $this->debug;

        if (!$first) {
            // disable debug mode on all but the first initialization
            $this->debug = false;
        }

        // will not work with --process-isolation
        $first = false;

        try {
            parent::initializeContainer();
        } catch (\Exception $e) {
            $this->debug = $debug;
            throw $e;
        }

        $this->debug = $debug;
    }
}
