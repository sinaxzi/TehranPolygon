<?php

namespace App\Console\Commands;

use App\Models\District;
use Illuminate\Console\Command;

class InsertCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert tehran polygon json to the database';


    public function handle()
    {
        $file = file_get_contents(storage_path('app/private/database/tehran.json'));
        $records = json_decode($file,true);

        foreach ($records['tehranSuburb']['features']  as $feature) {

            District::create([
                'name' => $feature['properties']['name:en'],
                'shape' => $feature['geometry']['coordinates'][0]
            ]);
        }
    }
}
