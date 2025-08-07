<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Libraries\TerminalHelper;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;


class WorldLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $command = $this->command;
        $columnWidth = TerminalHelper::getTerminalWidth();
        
        $files = [
            'regions.sql',
            'subregions.sql',
            'countries.sql',
            'states.sql',
            'cities.sql',
        ];
        
        $tables = [
            'app_locations_cities',
            'app_locations_states',
            'app_locations_countries',
            'app_locations_subregions',
            'app_locations_regions',
        ];
        
        $command->line('');
        // $command->line("  \e[44m INFO \e[0m Truncate Table.\n");
        
        foreach($tables as $table) {
            // $end = " \e[93m RUNNING \e[0m";
            // $line = "  {$table} " . str_repeat('.', $columnWidth - strlen($table) - strlen($end) ) . $end;
            // $command->line($line);
            
            DB::unprepared("SET FOREIGN_KEY_CHECKS=0");
            
            if (DB::table($table)->exists()) {
                $limit = 1000;
                $totalData = DB::table($table)->count();
                $round = round($totalData / $limit);
                
                $output = new ConsoleOutput();
                
                $bar = new ProgressBar($output, $round);
                $bar->setFormat("  \e[93mTruncate\e[0m %namestep% [%bar%] %percent:3s%% | %current%/%max% - %elapsed%");
                $bar->setMessage(str_pad($table, 30), "namestep");
                
                while(DB::table($table)->count() > 0) {
                    DB::table($table)->limit($limit)->delete();
                    $bar->advance();
                }
                
                $bar->finish();
                $command->line("");
                
            }
            DB::unprepared("SET FOREIGN_KEY_CHECKS=1");
            
            // $end = "\e[32mDONE\e[0m";
            // $line = "  {$table} " . str_repeat('.', $columnWidth - strlen($table) - strlen($end) - strlen($durationString) );
            // $line .= "{$durationString} {$end}";
            // $command->line($line);
        }
        
        $command->line("");
        
        foreach($files as $file) {
            $path = database_path("sql/{$file}");
            
            $output = new ConsoleOutput();
            
            $bar = new ProgressBar($output);
            $bar->setFormat("  \e[92mInserting\e[0m %namestep% [%bar%] %current%");
            $bar->setMessage(str_pad($file, 30), "namestep");
            
            $bar->start();
            
            if (! file_exists($path)) {
                $command->error("");
                $bar->setMessage("\e[31mError\e[0m {$file}");
                $bar->finish();
                $command->line("");
                
                continue;
            }
            
            $handle = fopen($path, 'r');
            $buffer = '';
            
            if (! $handle) {
                $command->error("");
                $bar->setMessage("\e[31mError\e[0m {$file}");
                $bar->finish();
                $command->line("");
                continue;
            }
            
            while (!feof($handle)) {
                $line = fgets($handle);
                $buffer .= $line;
                
                if (substr(trim($line), -1) === ';') {
                    DB::unprepared($buffer);
                    $buffer = '';
                    
                    $bar->advance();
                }
            }
            
            if (!empty(trim($buffer))) {
                DB::unprepared($buffer);
            }
            
            fclose($handle);
            
            $bar->finish();
            $command->line("");
        }
        
        $command->line("");
    }
}
