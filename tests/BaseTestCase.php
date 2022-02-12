<?php

namespace Tests;

use Orchestra\Testbench\TestCase;
use KKomelin\TranslatableStringExporter\Providers\ExporterServiceProvider;

class BaseTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ExporterServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->setBasePath(__DIR__ . DIRECTORY_SEPARATOR . '__fixtures');
        $app['config']->set('laravel-translatable-string-exporter.directories', [
            'resources',
        ]);

        $app['config']->set('laravel-translatable-string-exporter.sort-keys', true);

        $app['config']->set('laravel-translatable-string-exporter.functions', [
            '__',
            '_t',
            '@lang',
        ]);
    }

    protected function removeJsonLanguageFiles()
    {
        $path = $this->getTranslationFilePath('*');
        $files = glob($path); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }
    }

    protected function createTestView($content)
    {
        file_put_contents(resource_path('views/index.blade.php'), $content);
    }

    /**
     * @deprecated 2.0.0 Replace base_path('lang') with lang_path().
     */
    protected function getTranslationFilePath($language)
    {
        $lang_path = 'lang/' . $language . '.json';
        return is_dir(resource_path('lang')) ? resource_path($lang_path) : base_path($lang_path);
    }

    protected function getTranslationFileContent($language)
    {
        $path = $this->getTranslationFilePath($language);
        $content = file_get_contents($path);
        return json_decode($content, true);
    }

    protected function writeToTranslationFile($language, $content)
    {
        $path = $this->getTranslationFilePath($language);
        file_put_contents($path, $content);
    }
}
