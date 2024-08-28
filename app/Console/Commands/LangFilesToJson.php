<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LangFilesToJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert Laravel language files from PHP to JSON';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function convert($data)
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->convert($value);
            } else {
                if (strpos($value, ':') !== false) {
                    $value = preg_replace('/:(\w+)/', '{$1}', $value);
                }
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $sourceDir = resource_path('lang/');
        $targetDir = resource_path('lang/');

        $languages = array_diff(scandir($sourceDir), ['.', '..']);

        foreach ($languages as $language) {
            $languageDir = $sourceDir . $language . '/';
            $files       = array_diff(scandir($languageDir), ['.', '..']);

            $translations = [];

            foreach ($files as $file) {
                $filePath    = $languageDir . $file;
                $translation = require $filePath;

                $translation = $this->convert($translation);

                $translations[str_replace('.php', '', $file)] = $translation;
            }

            $targetPath = $targetDir . $language . '.json';

            file_put_contents($targetPath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $this->info('Language files compiled to JSON successfully!');
    }
}
