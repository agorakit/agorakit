<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;

class ConvertTranslationsToGettext extends Command
{
    /**
     * Signature de la commande.
     *
     * Modes :
     *   (défaut)    : remplace les clés par les traductions anglaises
     *   --dry-run   : affiche ce qui serait modifié sans écrire
     *   --report    : liste toutes les clés trouvées dans le code (fichier + ligne),
     *                 indique lesquelles existent dans les traductions anglaises,
     *                 et n'écrit AUCUNE modification.
     *
     * @var string
     */
    protected $signature = 'translations:to-gettext
                            {--path=* : Un ou plusieurs chemins à scanner (défaut: app, resources)}
                            {--dry-run : Afficher les modifications sans les appliquer}
                            {--report : Lister toutes les clés trouvées sans rien modifier}';

    /**
     * @var string
     */
    protected $description = 'Remplace les clés de traduction Laravel (__(), trans(), @lang(), etc.) par leur texte anglais (compatible gettext).';

    public function handle(): int
    {
        $paths  = $this->option('path') ?: [app_path(), resource_path()];
        $dryRun = (bool) $this->option('dry-run');
        $report = (bool) $this->option('report');

        if ($dryRun && $report) {
            $this->error('--dry-run et --report sont incompatibles (--report n\'écrit jamais).');
            return Command::FAILURE;
        }

        $englishTranslations = $this->loadEnglishTranslations();

        if (empty($englishTranslations)) {
            $this->error('Aucune traduction anglaise trouvée dans lang/en (ou resources/lang/en).');
            return Command::FAILURE;
        }

        $this->info('Traductions anglaises chargées : ' . count($englishTranslations));

        foreach ($englishTranslations as $key => $entr) {
            $this->info($key . ' : ' . $entr);
        }

        // En mode report : on collecte les occurrences avec fichier + ligne
        $reportEntries = []; // [ ['key'=>..., 'file'=>..., 'line'=>..., 'found'=>bool, 'snippet'=>...], ... ]

        $modifiedFilesCount = 0;
        $untouchedKeys      = [];

        foreach ($paths as $path) {
            if (!File::isDirectory($path)) {
                if (File::exists($path)) {
                    $files = [new \Symfony\Component\Finder\SplFileInfo($path, dirname($path), basename($path))];
                } else {
                    $this->warn("Chemin introuvable, ignoré : {$path}");
                    continue;
                }
            } else {
                $files = File::allFiles($path);
            }

            foreach ($files as $file) {
                // Inclut .php et .blade.php (getExtension() renvoie 'php' pour les deux)
                if (!str_ends_with($file->getFilename(), '.php')) {
                    continue;
                }

                $filePath = $file->getPathname();
                $content  = File::get($filePath);
                $original = $content;

                $regexes = $this->getRegexes();

                if ($report) {
                    // Mode report : on parcourt chaque pattern, on retrouve les offsets/lignes
                    foreach ($regexes as $pattern => $_) {
                        if (!preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)) {
                            continue;
                        }

                        foreach ($matches as $match) {
                            // $match[2][0] = clé, $match[0][1] = offset complet
                            $key    = $match[2][0];
                            $offset = $match[0][1];
                            $line   = substr_count(substr($content, 0, $offset), "\n") + 1;
                            $found  = isset($englishTranslations[$key]);

                            $reportEntries[] = [
                                'key'     => $key,
                                'file'    => str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath),
                                'line'    => $line,
                                'found'   => $found,
                                'snippet' => trim($match[0][0]),
                            ];

                            if (!$found) {
                                $untouchedKeys[$key] = true;
                            }
                        }
                    }
                } else {
                    // Mode remplacement (avec ou sans --dry-run)
                    foreach ($regexes as $pattern => $callback) {
                        $content = preg_replace_callback(
                            $pattern,
                            function ($matches) use ($englishTranslations, $callback, &$untouchedKeys) {
                                $key = $matches[2];

                                if (!isset($englishTranslations[$key])) {
                                    $untouchedKeys[$key] = true;
                                    return $matches[0];
                                }

                                $translation = addcslashes($englishTranslations[$key], "'\"\\");
                                return $callback($translation, $matches);
                            },
                            $content
                        );
                    }

                    if ($content !== $original) {
                        $modifiedFilesCount++;
                        $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath);

                        if ($dryRun) {
                            $this->line("[DRY-RUN] Fichier qui serait modifié : {$relativePath}");
                        } else {
                            File::put($filePath, $content);
                            $this->line("Fichier modifié : {$relativePath}");
                        }
                    }
                }
            }
        }

        // ========== SORTIE MODE REPORT ==========
        if ($report) {
            $this->newLine();

            if (empty($reportEntries)) {
                $this->info('Aucune clé de traduction trouvée dans les fichiers scannés.');
                return Command::SUCCESS;
            }

            // Tri par fichier puis ligne
            usort($reportEntries, function ($a, $b) {
                return [$a['file'], $a['line']] <=> [$b['file'], $b['line']];
            });

            $this->info('=== RAPPORT DES CLÉS DE TRADUCTION ===');
            $this->newLine();

            $rows = array_map(function ($e) {
                return [
                    $e['found'] ? '✅' : '❌',
                    $e['key'],
                    $e['file'],
                    $e['line'],
                    $e['snippet'],
                ];
            }, $reportEntries);

            $this->table(
                ['Trouvée', 'Clé', 'Fichier', 'Ligne', 'Extrait'],
                $rows
            );

            // Résumé
            $total      = count($reportEntries);
            $foundCount = count(array_filter($reportEntries, fn($e) => $e['found']));
            $missing    = $total - $foundCount;
            $uniqueKeys = count(array_unique(array_column($reportEntries, 'key')));

            $this->newLine();
            $this->info("Total occurrences : {$total}");
            $this->info("Clés uniques     : {$uniqueKeys}");
            $this->info("Résolues (✅)    : {$foundCount}");
            $missing > 0
                ? $this->warn("Non résolues (❌) : {$missing}")
                : $this->info("Non résolues (❌) : 0");

            if ($missing > 0) {
                $this->newLine();
                $this->warn('Clés introuvables dans lang/en :');
                $missingKeys = array_unique(array_map(
                    fn($e) => $e['key'],
                    array_filter($reportEntries, fn($e) => !$e['found'])
                ));
                foreach ($missingKeys as $k) {
                    $this->line("  - {$k}");
                }
                $this->newLine();
                $this->line('Astuce : les clés dynamiques (ex: trans($var), trans("auth.{$rule}"))');
                $this->line('sont détectées et laissées telles quelles. Elles doivent être traitées manuellement.');
            }

            return Command::SUCCESS;
        }

        // ========== SORTIE MODE REMPLACEMENT ==========
        if (!empty($untouchedKeys)) {
            $this->newLine();
            $this->warn(count($untouchedKeys) . ' clé(s) non trouvée(s) dans les traductions anglaises :');
            foreach (array_slice(array_keys($untouchedKeys), 0, 30) as $k) {
                $this->line("  - {$k}");
            }
            if (count($untouchedKeys) > 30) {
                $this->line('  ... (tronqué, ' . (count($untouchedKeys) - 30) . ' de plus)');
            }
            $this->line('Utilisez --report pour voir où ces clés sont utilisées.');
        }

        $this->newLine();
        $this->info($modifiedFilesCount === 0
            ? 'Aucun fichier à modifier.'
            : "Terminé. {$modifiedFilesCount} fichier(s) " . ($dryRun ? 'seraient modifiés.' : 'modifiés.'));

        return Command::SUCCESS;
    }

    /**
     * Retourne un tableau : pattern => callback($translation, $matches) qui reconstruit l'appel.
     *
     * Couvre :
     *   - trans('')            - trans('')            - trans('') - trans('')
     *   - trans_choice('', $n) - trans_choice('', $n)
     *   - @lang('')         - @lang('', $n)
     */
    protected function getRegexes(): array
    {
        return [
            // trans('') / trans('') / trans('') avec arguments optionnels après la clé
            '/(?<![\w\\\\])(?:__|trans|\\\\?Lang::get)\(\s*([\'"])(.*?)\1\s*(?:,[^)]*)?\)/s'
            => fn($t) => "trans('{$t}')",

            // trans_choice('', $n) / trans_choice('', $n) — on préserve les arguments
            '/(?<![\w\\\\])(?:trans_choice|\\\\?Lang::choice)\(\s*([\'"])(.*?)\1(\s*,.*?)\)/s'
            => fn($t, $m) => "trans_choice('{$t}'{$m[3]})",

            // @lang('') / @lang('', $n)
            '/@(?:lang|choice)\(\s*([\'"])(.*?)\1(\s*,[^)]*)?\)/s'
            => fn($t, $m) => "@lang('{$t}'" . ($m[3] ?? '') . ')',
        ];
    }

    /**
     * Charge toutes les traductions anglaises dans un tableau plat.
     * Gère lang/en/ (Laravel 11/12), resources/lang/en/ (legacy), et lang/en.json.
     */
    protected function loadEnglishTranslations(): array
    {
        $translations = [];
        $locales      = ['en', 'en_US', 'en_GB'];

        foreach ($locales as $locale) {
            $langPath = lang_path($locale);
            if (!File::isDirectory($langPath)) {
                $langPath = resource_path("lang/{$locale}");
            }

            if (!File::isDirectory($langPath)) {
                continue;
            }

            $basePath = rtrim($langPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            foreach (File::allFiles($langPath) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $fileTranslations = require $file->getPathname();
                if (!is_array($fileTranslations)) {
                    continue;
                }

                // 🔑 On déduit le préfixe depuis le chemin relatif :
                // lang/en/groups.php            => "groups"
                // lang/en/admin/groups.php      => "admin.groups"
                // lang/en/packages/foo/bar.php  => "packages.foo.bar"
                $relative    = str_replace($basePath, '', $file->getPathname());
                $relative    = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
                $prefix      = preg_replace('/\.php$/', '', $relative);
                $prefix      = str_replace('/', '.', $prefix);

                // Aplatir les tableaux imbriqués
                $flat = Arr::dot($fileTranslations);

                foreach ($flat as $key => $value) {
                    if (!is_string($value)) {
                        continue;
                    }

                    // Clé finale = <fichier>.<chemin aplati>
                    $fullKey = $prefix === '' ? $key : "{$prefix}.{$key}";
                    $translations[$fullKey] = $value;

                    // On ajoute aussi la forme brute (sans préfixe), car beaucoup de code
                    // utilise trans('Title') en s'appuyant sur un namespace implicite
                    // dans les vues Blade par exemple. Ça évite les faux négatifs.
                    if (!isset($translations[$key])) {
                        $translations[$key] = $value;
                    }
                }
            }

            // Fichier JSON global : les clés sont déjà les chaînes anglaises
            $jsonPath = lang_path("{$locale}.json");
            if (!File::exists($jsonPath)) {
                $jsonPath = resource_path("lang/{$locale}.json");
            }

            if (File::exists($jsonPath)) {
                $jsonTranslations = json_decode(File::get($jsonPath), true);
                if (is_array($jsonTranslations)) {
                    foreach ($jsonTranslations as $key => $value) {
                        if (is_string($value)) {
                            // Le JSON global ne préfixe pas par le nom de fichier
                            $translations[$key] = $value;
                        }
                    }
                }
            }
        }

        return $translations;
    }
}
