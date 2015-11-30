<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportActions extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'import:actions';

  /**
  * The console command description.
  *
  * @var string
  */
  protected $description = 'Import actions form a csv file. Experimental';


  /*
  This is the offset used in ides to avoid collision with existing data. Time will tell if it's agood idea. Curently not used
  */
  public $id_offset = 10;

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
    // open csv file from storage
    $csv = Reader::createFromPath(storage_path('import/actions.csv'));

    //get the first row, usually the CSV header
    $headers = $csv->fetchOne();

    // validate headers

    //get 25 rows starting from the 11th row
    $res = $csv->setOffset(1)->fetchAll();

    // build a nice associative array from the csv
    foreach ($res as $data)
    {
      foreach ($headers as $key=>$header)
      {
        $action[$header] = $data[$key];
      }
      $actions_data[] = $action;
    }



    foreach ($actions_data as $action_data)
    {
      if (isset($action_data['id']))
      {
        $action = \App\Action::firstOrNew(['id' => $action_data['id']]);


        $action->name = $action_data['name'];
        $action->body = $action_data['body'];

        $action->start = \Carbon\Carbon::parse($action_data['start']);
        $action->stop = \Carbon\Carbon::parse($action_data['stop']);

        $action->location = $action_data['location'];
        $action->user_id = 1;

        // create the group if not existing yet and associate it.
        if (!empty($action_data['group_id']) && !empty($action_data['group']))
        {
          $group = \App\Group::firstOrNew(['id' => $action_data['group_id'] + $this->id_offset ]);
          $group->name = $action_data['group'];
          $group->body = 'No description';
          $group->save();
          $action->group()->associate($group);
          $this->info($group->name . ' GROUP has been created/updated in db');
          $action->save();

          if ($action->isInvalid())
          {
            $this->error($action->getErrors());
          }
          else
          {
            $this->info($action->name . ' ACTION has been created/updated in db');
          }

        }

      }

    }
  }


}
