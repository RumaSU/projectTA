<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RecursiveMigrateFresh extends Command
{
    protected $signature = 'migrate:recursive-fresh';
    protected $description = 'Drop all tables and rerun all migrations including subdirectories';

    public function handle()
    {
        $this->dropAllTables();

        $migrationPath = database_path('migrations');
        $migrationFiles = $this->getMigrationFilesRecursively($migrationPath);
        
        $this->line("\n  \e[44m INFO \e[0m Running migrations.\n");
        $this->migrateFiles($migrationFiles);

        $finalMessage = "\n  \e[44m INFO \e[0m All migrations have been executed recursively after dropping all tables.\n"; // Latar belakang hijau dengan teks hitam
        $this->line($finalMessage);
    }

    private function dropAllTables()
    {
        $this->line("\n  \e[44m INFO \e[0m Running drop all tables.\n");
        Artisan::call('db:wipe');
        $this->line("\n  \e[44m INFO \e[0m Dropped all tables successfully.\n\n");
    }

    private function getMigrationFilesRecursively($path)
    {
        return File::allFiles($path);
    }
    
    private function migrateFiles($migrationFiles) {
        $columnWidth = 120;
        foreach ($migrationFiles as $file) {
            $path = $file->getRelativePathname();
            $relativePathName = $file->getRelativePathName();
    
            $startTime = microtime(true);
    
            $exitCode = Artisan::call('migrate', ['--path' => "database/migrations/$path"]);
    
            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
    
            $status = $exitCode === 0 ? "\e[32mDONE\e[0m" : "\e[31mFAILED\e[0m";
            $durationString = "{$duration}s";
            
            $dots = " " . str_repeat('.', $columnWidth - strlen($relativePathName) - strlen($durationString) - strlen($status) );
            $output = $relativePathName . $dots;
            $output .= " {$durationString} {$status}";
    
            $exitCode === 0 ? $this->line($output) : $this->error($output);
        }
    }
}
