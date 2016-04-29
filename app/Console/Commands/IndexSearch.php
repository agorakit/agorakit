<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Config;
use TeamTNT\TNTSearch\TNTSearch;

class IndexSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mobilizator:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index Search Description';

    protected $name = 'mobilizator:index';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tnt = new TNTSearch;

        // Get current dbconfig
        $dbConfig = array_merge(Config::get('database.connections.' . Config::get('database.default')));

        $dbConfig['storage'] = storage_path('app/');

        $tnt->loadConfig($dbConfig);

        $indexer = $tnt->createIndex('name.index');
        $indexer->query('SELECT id, name FROM groups;');
        //$indexer->setLanguage('german');
        $indexer->run();
    }
}
