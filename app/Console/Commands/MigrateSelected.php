<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Finder\SplFileInfo;
// use SplFileInfo;

class MigrateSelected extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:selected {--fresh=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all migrations selected';

    /**
     * File selected
     * 
     * @var array
     * database_path('migrations') // ...\database\migrations\...
     */
    private $selected_migrate = [
        'db\0000_000_session_table.php',
        'db\0001_000_user.php',
        'db\0004_000_signatures.php',
        'db\0080_001_file_signatures.php',
        'db\9999_000_references_table.php'
    ];
    
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $freshInput = $this->option('fresh');
        if(!$this->validateFreshOption($freshInput)) {
            $this->error("Invalid value for --fresh: '{$freshInput}'. Allowed values: true, false, 1, 0.");
            return 1;
        }
        
        $fresh = filter_var($freshInput, FILTER_VALIDATE_BOOLEAN);
        if ($fresh) $this->dropAllTables();
        
        $migrationFiles = $this->setFileMigrationSelected();
        
        $this->line('');
        $this->line("  \e[44m INFO \e[0m Running migrations.\n");
        
        $finalMessage = "  \e[44m INFO \e[0m ";
        
        if (count($migrationFiles) > 0) {
            $this->migrateFiles($migrationFiles);
            
            $finalMessage .= "All migrations have been executed.\n";
        } else {
            $finalMessage .= "All selected files is not found.\n";
        }
        
        $this->line('');
        $this->line($finalMessage);
    }
    
    private function validateFreshOption($freshInput) {
        if (gettype($freshInput) == 'string') strtolower($freshInput);
        
        $allowed = ['true', 'false', '1', '0', 1, 0, true, false];
        if (!in_array($freshInput, $allowed, true)) return false;
        
        return true;
    }
    
    private function dropAllTables()
    {
        $this->line("\n  \e[44m INFO \e[0m Running drop all tables.\n");
        Artisan::call('db:wipe');
        $this->line("\n  \e[44m INFO \e[0m Dropped all tables successfully.\n\n");
    }
    
    private function setFileMigrationSelected() {
        $this->line("  \e[44m INFO \e[0m Set file migrations.\n");
        
        $migrationPath = database_path('migrations');
        $migrationSelected = [];
        
        foreach($this->selected_migrate as $selectFile) {
            $migrationSelectPath = $migrationPath . DIRECTORY_SEPARATOR . $selectFile;
            
            if (File::exists($migrationSelectPath)) {
                $migrationSelected[] = new SplFileInfo(
                    $migrationSelectPath,
                    $migrationPath,
                    $selectFile
                );
            }
        }
        
        return $migrationSelected;
    }
    
    private function migrateFiles($migrationFiles) {
        $columnWidth = 120;
        foreach ($migrationFiles as $file) {
            // $this->line($file);
            $path = $file->getRelativePathname();
            $relativePathName = $file->getRelativePathName();
    
            $startTime = microtime(true);
    
            $exitCode = Artisan::call('migrate', ['--path' => "database/migrations/$path"]);
    
            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
    
            $status = $exitCode === 0 ? "\e[32mDONE\e[0m" : "\e[31mFAILED\e[0m";
            $durationString = "{$duration}s";
            
            $dots = " " . str_repeat('.', $columnWidth - strlen($relativePathName) - strlen($durationString) - strlen($status) );
            $output = '  ' . $relativePathName . $dots;
            $output .= " {$durationString} {$status}";
    
            $exitCode === 0 ? $this->line($output) : $this->error($output);
        }
    }
    
}
