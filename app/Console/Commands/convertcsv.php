<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Response;
use View;
use File;

class convertcsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:file {source_filename} {generate_filetype}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert a CSV file to JSON or XML file.';

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
     * @return int
     */
    public function handle()
    {
        $csvfile = $this->argument('source_filename');
        $generation_type = $this->argument('generate_filetype');

        // Generate JSON file
        if ($generation_type == 'json') {
            $filepath = resource_path() . '/csvfiles/' . $csvfile;

            // Response false if can't open file
            if (!($fp = fopen($filepath, 'r'))) {
                $this->error('Can not open file.');
            }
            
            // Read csv headers
            $key = fgetcsv($fp,"1024",",");
            
            // Parse csv rows into array
            $json = array();
            while ($row = fgetcsv($fp,"1024",",")) {
                $json[] = array_combine($key, $row);
            }

            // Release file handle
            fclose($fp);

            $jsongFile = time() . '_file.json';
            File::put(resource_path() . '/jsonfiles/' . $jsongFile, json_encode($json));
        }

        $this->info('File generated successfully.' . $csvfile . ' ' . $generation_type);
    }
}
