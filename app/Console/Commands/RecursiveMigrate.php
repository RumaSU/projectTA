<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class RecursiveMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:recursive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all migrations including subdirectories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');
        $this->line("  \e[44m INFO \e[0m Running migrations.\n");
        $migrationPath = database_path('migrations');
        $migrationFiles = $this->getMigrationFilesRecursively($migrationPath);
        
        $this->migrateFiles($migrationFiles);
        
        $this->line('');
        $finalMessage = "  \e[44m INFO \e[0m All migrations have been executed recursively.\n"; // Latar belakang hijau dengan teks hitam
        $this->line($finalMessage);
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
