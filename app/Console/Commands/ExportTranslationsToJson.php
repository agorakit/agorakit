<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;

class ExportTranslationsToJson extends Command
{
    protected $signature = 'translations:export-json
                        {--locale=* : Locales à exporter (défaut: toutes sauf en)}
                        {--path=* : Chemins des dossiers lang à scanner (défaut: lang, resources/lang)}
                        {--output= : Dossier de sortie (défaut: lang/)}
                        {--dry-run : Afficher ce qui serait écrit sans écrire}
                        {--no-pretty : Écrire du JSON compact (défaut: indenté)}
                        {--keep-keys : Garder les clés originales au lieu de la traduction anglaise}';
    protected $description = 'Exporte les traductions des langues non anglaises dans des fichiers JSON plats (lang/<locale>.json).';

    /**
     * Traductions anglaises chargées une seule fois (pour le mapping inverse si demandé).
     */
    protected array $englishTranslations = [];

    public function handle(): int
    {
        $outputPaths = $this->option('output')
            ? [$this->option('output')]
            : [lang_path()];

        $langRoots = $this->option('path') ?: array_values(array_filter([
            lang_path(),
            resource_path('lang'),
        ], fn($p) => File::isDirectory($p)));

        if (empty($langRoots)) {
            $this->error('Aucun dossier de langue trouvé (ni lang/ ni resources/lang/).');
            return Command::FAILURE;
        }

        $dryRun   = (bool) $this->option('dry-run');
        $pretty   = !$this->option('no-pretty');   // ✅ true par défaut
        $keepKeys = (bool) $this->option('keep-keys');

        // Charger les traductions anglaises (pour construire le mapping inverse si --keep-keys)
        $this->englishTranslations = $this->loadEnglishTranslations($langRoots);

        // 1. Détecter toutes les locales disponibles
        $locales = $this->detectLocales($langRoots);

        if (empty($locales)) {
            $this->error('Aucune locale détectée.');
            return Command::FAILURE;
        }

        // Filtrer selon --locale, et exclure l'anglais par défaut
        $requested = (array) $this->option('locale');
        if (!empty($requested)) {
            $locales = array_values(array_intersect($locales, $requested));
            if (empty($locales)) {
                $this->error('Aucune des locales demandées n\'existe. Locales trouvées : ' . implode(', ', $this->detectLocales($langRoots)));
                return Command::FAILURE;
            }
        } else {
            $locales = array_values(array_filter($locales, fn($l) => !preg_match('/^en(_|$)/i', $l)));
        }

        $this->info('Locales à exporter : ' . implode(', ', $locales));

        // 2. Pour chaque locale, charger et exporter
        $totalWritten = 0;

        foreach ($locales as $locale) {
            $this->newLine();
            $this->info("=== Locale : {$locale} ===");

            $translations = $this->loadTranslationsForLocale($locale, $langRoots);

            if (empty($translations)) {
                $this->warn("Aucune traduction trouvée pour {$locale}.");
                continue;
            }

            $this->line('Clés chargées : ' . count($translations));

            // Si --keep-keys, on garde les clés originales
            // Sinon, on remplace les clés par les valeurs (format JSON Laravel natif :
            // __( 'Welcome' ) résolu via lang/fr.json { "Welcome": "Bienvenue" })
            if (!$keepKeys) {
                $final = [];
                foreach ($translations as $key => $value) {
                    if (!is_string($value)) {
                        continue;
                    }
                    // Mapping : texte anglais → traduction française
                    // (si la clé source est déjà le texte anglais, on la garde)
                    $englishText = $this->englishTranslations[$key] ?? $key;
                    $final[$englishText] = $value;
                }
                $translations = $final;

                $this->line('Clés finales (format Laravel JSON, anglais → traduction) : ' . count($translations));
            }

            // 3. Écrire le fichier JSON
            $json = json_encode(
                $translations,
                ($pretty ? JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES : 0)
                    | JSON_THROW_ON_ERROR
            );

            foreach ($outputPaths as $outDir) {
                if (!File::isDirectory($outDir)) {
                    if (!$dryRun) {
                        File::makeDirectory($outDir, 0755, true);
                    }
                }

                $outFile = rtrim($outDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "{$locale}.json";
                $relative = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $outFile);

                if ($dryRun) {
                    $this->line("[DRY-RUN] Écrirait : {$relative} (" . strlen($json) . ' octets)');
                } else {
                    File::put($outFile, $json);
                    $this->line("Écrit : {$relative} (" . count($translations) . ' entrées)');
                    $totalWritten++;
                }
            }
        }

        $this->newLine();
        $this->info($dryRun
            ? "Terminé (dry-run). {$totalWritten} fichier(s) auraient été écrit(s)."
            : "Terminé. {$totalWritten} fichier(s) écrit(s).");

        return Command::SUCCESS;
    }

    /**
     * Détecte toutes les locales sous les racines lang données.
     * Détecte :
     *   - Les dossiers : lang/fr/, lang/es/
     *   - Les fichiers JSON : lang/fr.json, lang/es.json
     */
    protected function detectLocales(array $roots): array
    {
        $locales = [];

        foreach ($roots as $root) {
            if (!File::isDirectory($root)) {
                continue;
            }

            // Dossiers de locale
            foreach (File::directories($root) as $dir) {
                $locales[basename($dir)] = true;
            }

            // Fichiers JSON (lang/fr.json)
            foreach (File::files($root) as $file) {
                if (str_ends_with($file->getFilename(), '.json')) {
                    $locales[basename($file->getFilename(), '.json')] = true;
                }
            }
        }

        ksort($locales);

        return array_keys($locales);
    }

    /**
     * Charge toutes les traductions (PHP + JSON) pour une locale donnée,
     * sous forme de tableau plat avec clés préfixées par le fichier.
     *
     * Ex :
     *   lang/fr/groups.php : ['title' => 'Groupes']
     *   => ['groups.title' => 'Groupes']
     *
     *   lang/fr.json : { "Welcome": "Bienvenue" }
     *   => ['Welcome' => 'Bienvenue']
     */
    protected function loadTranslationsForLocale(string $locale, array $roots): array
    {
        $translations = [];

        foreach ($roots as $root) {
            if (!File::isDirectory($root)) {
                continue;
            }

            // 1. Fichiers JSON globaux : lang/<locale>.json
            $jsonFile = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "{$locale}.json";
            if (File::exists($jsonFile)) {
                $json = json_decode(File::get($jsonFile), true);
                if (is_array($json)) {
                    foreach ($json as $k => $v) {
                        if (is_string($v)) {
                            $translations[$k] = $v;
                        }
                    }
                }
            }

            // 2. Fichiers PHP : lang/<locale>/<...>.php
            $langDir = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $locale;
            if (!File::isDirectory($langDir)) {
                continue;
            }

            $basePath = rtrim($langDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            $files = File::allFiles($langDir);
            usort($files, fn($a, $b) => strcmp($a->getPathname(), $b->getPathname()));

            foreach ($files as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $fileTranslations = require $file->getPathname();
                if (!is_array($fileTranslations)) {
                    continue;
                }

                // Préfixe = chemin relatif sans .php, séparateurs → points
                $relative = str_replace($basePath, '', $file->getPathname());
                $relative = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
                $prefix   = preg_replace('/\.php$/', '', $relative);
                $prefix   = str_replace('/', '.', $prefix);

                $flat = Arr::dot($fileTranslations);

                foreach ($flat as $key => $value) {
                    if (!is_string($value)) {
                        continue;
                    }

                    $fullKey = $prefix === '' ? $key : "{$prefix}.{$key}";
                    $translations[$fullKey] = $value;

                    // Doublon volontaire sans préfixe (voir script précédent)
                    if (!isset($translations[$key])) {
                        $translations[$key] = $value;
                    }
                }
            }
        }

        return $translations;
    }

    /**
     * Charge uniquement les traductions anglaises (mêmes règles que les autres locales).
     * Utilisé pour faire le mapping clé → texte anglais si --keep-keys n'est pas demandé.
     */
    protected function loadEnglishTranslations(array $roots): array
    {
        $translations = [];

        foreach (['en', 'en_US', 'en_GB'] as $locale) {
            $translations = array_merge(
                $translations,
                $this->loadTranslationsForLocale($locale, $roots)
            );
        }

        return $translations;
    }
}
