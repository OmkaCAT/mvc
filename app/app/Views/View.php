<?php

namespace App\Views;

use App\Core\Application;
use App\Core\Config;
use App\Core\Exceptions\FileNotFoundException;

class View
{
    private string $viewPath;

    public function __construct()
    {
        $app = Application::getInstance();

        $basePath = $app->getBasePath();

        /** @var Config $config */
        $config = $app->resolve('config');
        $viewDir = $config->get('app.views_dir');

        $this->viewPath = $basePath . '/' . $viewDir . '/';
    }

    public function render(string $path, array $data): string
    {
        $fullPath = $this->findViewFile($path);

        return $this->getContents($fullPath, $data);
    }

    protected function getContents(string $path, array $data): string
    {
        $obLevel = ob_get_level();

        ob_start();

        try {
            $__path = $path;
            $__data = $data;

            (static function () use ($__path, $__data) {
                extract($__data, EXTR_SKIP);

                return require $__path;
            })();
        } catch (\Throwable $e) {
            $this->handleViewException($e, $obLevel);
        }

        return ltrim(ob_get_clean());
    }

    private function handleViewException(\Throwable $e, $obLevel)
    {
        while (ob_get_level() > $obLevel) {
            ob_end_clean();
        }

        throw $e;
    }

    private function findViewFile(string $path): string
    {
        // todo parse $path
        // todo надо ли layout?
        $fullPath = $this->viewPath . $path . '.php';

        if (!file_exists($fullPath)) {
            throw new FileNotFoundException("Unable to locate view file for view '$path'");
        }

        return $fullPath;
    }
}