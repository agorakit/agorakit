<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportCalendarEvents extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
    protected $signature = 'import:calendarevents';

  /**
  * The console command description.
  *
  * @var string
  */
    protected $description = 'Import calendar events form a csv file. Experimental';


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
        $csv = Reader::createFromPath(storage_path('import/calendarevents.csv'));

      //get the first row, usually the CSV header
        $headers = $csv->fetchOne();

      // validate headers

      //get 25 rows starting from the 11th row
        $res = $csv->setOffset(1)->fetchAll();

      // build a nice associative array from the csv
        foreach ($res as $data) {
            foreach ($headers as $key => $header) {
                $event[$header] = $data[$key];
            }
            $events_data[] = $event;
        }



        foreach ($events_data as $event_data) {
            if (isset($event_data['id'])) {
                $event = \App\CalendarEvent::firstOrNew(['id' => $event_data['id']]);


                $event->name = $event_data['name'];
                $event->body = $event_data['body'];

                $event->start = \Carbon\Carbon::parse($event_data['start']);
                $event->stop = \Carbon\Carbon::parse($event_data['stop']);

                $event->location = $event_data['location'];
                $event->user_id = 1;

              // create the group if not existing yet and associate it.
                if (!empty($event_data['group_id']) && !empty($event_data['group'])) {
                    $group = \App\Group::firstOrNew(['id' => $event_data['group_id'] + $this->id_offset ]);
                    $group->name = $event_data['group'];
                    $group->body = 'No description';
                    $group->save();
                    $event->group()->associate($group);
                    $this->info($group->name . ' GROUP has been created/updated in db');
                    $event->save();

                    if ($event->isInvalid()) {
                        $this->error($event->getErrors());
                    } else {
                        $this->info($event->name . ' EVENT has been created/updated in db');
                    }
                }
            }
        }
    }
}
