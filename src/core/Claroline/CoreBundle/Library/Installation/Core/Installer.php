<?php

namespace Claroline\CoreBundle\Library\Installation\Core;

use Symfony\Component\HttpKernel\Kernel;
use Claroline\CoreBundle\Library\Installation\BundleMigrator;

class Installer
{
    private $kernel;
    private $migrator;

    public function __construct(Kernel $kernel, BundleMigrator $migrator)
    {
        $this->kernel = $kernel;
        $this->migrator = $migrator;
    }

    public function install()
    {
        $this->createCoreSchema();
    }

    public function uninstall()
    {
        $this->dropCoreSchema();
    }

    private function createCoreSchema()
    {
        $bundles = $this->getRegisteredCoreBundles();

        foreach ($bundles as $bundle) {
            $this->migrator->createSchemaForBundle($bundle);
        }
    }

    private function dropCoreSchema()
    {
        $bundles = $this->getRegisteredCoreBundles();

        foreach ($bundles as $bundle) {
            $this->migrator->dropSchemaForBundle($bundle);
        }
    }

    private function getRegisteredCoreBundles()
    {
        $bundles = $this->kernel->getBundles();
        $coreBundles = array();

        foreach ($bundles as $bundle) {
            if (strpos($bundle->getPath(), 'core') !== false) {
                $coreBundles[] = $bundle;
            }
        }

        return $coreBundles;
    }
}