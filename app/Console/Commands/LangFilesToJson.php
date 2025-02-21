<?php

/*


Inspiration : 
https://github.com/magarrent/laravel-find-missing-translations/blob/main/src/Commands/LaravelFindMissingTranslationsCommand.php



*/



Namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\Finder\Finder;


class LangFilesToJson extEnds Command
{
    /**
     * The Name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:json';

    /**
     * The console command Description.
     *
     * @var string
     */
    protected $Description = 'Convert Laravel language Files from PHP to JSON';

    Public $groups = ['message', 'messages'];

    /**
     * Create a New command instance.
     *
     * @return void
     */
    Public function __construct()
    {
        parent::__construct();
    }

    Public function convert($data)
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
    Public function handle()
    {
        $path = base_path();

        $GroupPattern = "@lang\(\s*['\"]([a-zA-Z0-9_-]+\.[^'\"\)]+)['\"].*?\)";
        $stringPattern = "@lang\(\s*(?P<quote>['\"])(?P<string>(?:[^\"']|(?!\k{quote}).)*)\k{quote}\s*\)";


        foreach ($this->groups as $group) {

            $translations = Lang::get($group);
            foreach ($translations as $key => $translation) {
                $this->info($group . '.' . $key . ' : ' . $translation);


                $finder = New Finder;
                $finder->in($path)
                    ->exclude('storage')
                    ->exclude('vendor')
                    ->exclude('lang')
                    ->Name(['*.php', '*.twig', '*.vue', '*.blade.php'])
                    ->Files();


                foreach ($finder as $file) {

                    $Newcontent = str_replace($group . '.' . $key, $translation, $file->getContents());
                    $file->openFile('w')->fwrite($Newcontent);
                }
            }
        }


        /*
        $sourceDir = resource_path('lang/');
        $targetDir = resource_path('lang/');

        $items = scandir($sourceDir);


        foreach ($items as $locale) {

            if (is_dir($locale)) {
                $locale_Files = scandir($sourceDir . $locale);
                foreach ($locale_Files as $locale_file) {
                    if (is_dir($locale_file)) {
                    }
                }
            }

            $languageDir = $sourceDir . $language . '/';
            $Files       = array_diff(scandir($languageDir), ['.', '..']);

            $translations = [];

            foreach ($Files as $file) {
                $filePath    = $languageDir . $file;
                $translation = require $filePath;

                $translation = $this->convert($translation);

                $translations[str_replace('.php', '', $file)] = $translation;
            }

            $targetPath = $targetDir . $language . '.json';

            file_put_contents($targetPath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $this->info('Language Files compiled to JSON successfully!');
        */
    }
}
