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
        'db\0000_000_01_app_locations.php',
        'db\0000_000_session_table.php',
        'db\0001_000_user.php',
        'db\0003_000_document.php',
        'db\0003_001_document_signature.php',
        'db\0004_000_signatures.php',
        'db\0050_000_token.php',
        'db\0080_000_file.php',
        'db\0080_001_file_entity.php',
        'db\0100_000_jobs_docs_table.php',
        // 'db\9999_000_references_table.php',
        'db\9999_001_references_table_session.php',
        'db\9999_002_references_table_users.php',
        'db\9999_003_references_table_documents.php',
        'db\9999_004_references_table_documents_signatures.php',
        'db\9999_005_references_table_signatures.php',
        'db\9999_006_references_table_files.php',
        '0001_01_01_000002_create_jobs_table.php',
        '0001_01_01_000001_create_cache_table.php',
    ];
    
    
    
    
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $freshInput = $this->option('fresh');
        if(!$this->validateBooleanOption($freshInput)) {
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
    
    /**
     * Validate boolean option from string or boolean input.
     *
     * @param  mixed  $input
     * @return bool
     */
    private function validateBooleanOption($input) {
        if (gettype($input) == 'string') strtolower($input);
        
        $allowed = ['true', 'false', '1', '0', 1, 0, true, false];
        if (!in_array($input, $allowed, true)) return false;
        
        return true;
    }
    
    /**
     * Drop all tables in the database.
     *
     * This method is called when the `--fresh` option is true.
     * It will drop all tables in the database using the `db:wipe` command.
     */
    private function dropAllTables()
    {
        $this->line("\n  \e[44m INFO \e[0m Running drop all tables.\n");
        Artisan::call('db:wipe');
        $this->line("\n  \e[44m INFO \e[0m Dropped all tables successfully.\n\n");
    }
    
    /**
     * Set file migrations to be executed.
     *
     * This method is called in the `handle` method and it will loop through the
     * `selected_migrate` property and check if each file exists in the
     * `database/migrations` directory. If the file exists, it will be added to
     * the `$migrationSelected` array.
     *
     * @return array An array of SplFileInfo objects.
     */
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
    
    /**
     * Executes migration files and outputs the result.
     *
     * This method iterates over an array of migration files, executing each
     * migration using the Artisan `migrate` command with the specific file path.
     * It calculates and displays the duration and status of each migration.
     * Successful migrations are displayed in green ("DONE"), and failed ones
     * are displayed in red ("FAILED"). The output is formatted to align with
     * a specified column width.
     *
     * @param array $migrationFiles An array of SplFileInfo objects representing the migration files to be executed.
     */

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
