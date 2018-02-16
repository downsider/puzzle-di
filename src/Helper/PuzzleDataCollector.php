<?php
/**
 * @package Puzzle-DI
 * @copyright Copyright © 2015 Danny Smart
 */

namespace Lexide\PuzzleDI\Helper;

use Composer\Installer\InstallationManager;
use Composer\Repository\RepositoryInterface;
use Composer\Package\Package;

class PuzzleDataCollector
{

    protected $installationManager;

    public function __construct(InstallationManager $installationManager)
    {
        $this->installationManager = $installationManager;
    }

    public function collectData(RepositoryInterface $repo)
    {
        $puzzleData = [];
        foreach ($repo->getPackages() as $package) {
            /** @var Package $package */
            $extra = $package->getExtra();
            $puzzleConfigKeys = [
                "downsider-puzzle-di",
                "lexide/puzzle-di"
            ];
            foreach ($puzzleConfigKeys as $configKey) {
                if (!empty($extra[$configKey]) && is_array($extra[$configKey])) {
                    foreach ($extra[$configKey] as $key => $config) {
                        if ($key == (string)(int)$key) {
                            continue;
                        }
                        if (!array_key_exists($key, $puzzleData)) {
                            $puzzleData[$key] = array();
                        }

                        $puzzleConfig = [
                            "name" => $package->getName(),
                            "path" => $this->installationManager->getInstallPath($package) . "/" . $config["path"]
                        ];
                        if (!empty($config["alias"])) {
                            $puzzleConfig["alias"] = $config["alias"];
                        }
                        $puzzleData[$key][] = $puzzleConfig;
                    }
                }
            }
        }
        return $puzzleData;
    }

} 
