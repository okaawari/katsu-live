<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FindDuplicateEmails extends Command
{
    protected $signature = 'users:find-duplicates {--export} {--clean}';
    protected $description = 'Find users with duplicate email addresses in old_users table';

    public function handle()
    {
        // Find duplicates
        $duplicates = DB::table('old_users')
            ->select('email')
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('No duplicate emails found in old_users table!');
            return;
        }

        $this->info("Found {$duplicates->count()} duplicate email addresses:");

        $totalDuplicateRecords = 0;

        foreach ($duplicates as $duplicate) {
            $users = DB::table('old_users')
                        ->where('email', $duplicate->email)
                        ->orderBy('created_at')
                        ->get();
            
            $totalDuplicateRecords += $users->count();
            
            $this->line("\nEmail: {$duplicate->email} ({$users->count()} occurrences)");
            
            foreach ($users as $user) {
                $this->line("  ID: {$user->id}, Name: {$user->name}, Created: {$user->created_at}");
            }
        }

        $this->info("\nTotal duplicate records: " . ($totalDuplicateRecords - $duplicates->count()));

        // Export option
        if ($this->option('export')) {
            $this->exportDuplicates();
        }

        // Clean option
        if ($this->option('clean')) {
            if ($this->confirm('Do you want to remove duplicates? (keeps the oldest record)')) {
                $this->cleanupDuplicates();
            }
        }
    }

    private function exportDuplicates()
    {
        $duplicateUsers = DB::table('old_users')
            ->whereIn('email', function($query) {
                $query->select('email')
                      ->from('old_users')
                      ->groupBy('email')
                      ->havingRaw('COUNT(*) > 1');
            })
            ->orderBy('email')
            ->orderBy('id')
            ->orderBy('sub_date')
            ->orderBy('expire_date')
            ->get();

        $filename = storage_path('app/duplicate_old_users_' . date('Y-m-d_H-i-s') . '.csv');
        
        $file = fopen($filename, 'w');
        
        // Get all columns from the first record to create header
        if ($duplicateUsers->isNotEmpty()) {
            $firstRecord = (array) $duplicateUsers->first();
            fputcsv($file, array_keys($firstRecord));
            
            foreach ($duplicateUsers as $user) {
                fputcsv($file, (array) $user);
            }
        }
        
        fclose($file);
        $this->info("Duplicates exported to: {$filename}");
        $this->info("Total records exported: {$duplicateUsers->count()}");
    }

    private function cleanupDuplicates()
    {
        // First, let's create a backup
        $backupTable = 'old_users_backup_' . date('YmdHis');
        
        DB::statement("CREATE TABLE {$backupTable} AS SELECT * FROM old_users");
        $this->info("Backup created: {$backupTable}");

        $duplicateEmails = DB::table('old_users')
            ->select('email')
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('email');

        $deletedCount = 0;

        DB::transaction(function() use ($duplicateEmails, &$deletedCount) {
            foreach ($duplicateEmails as $email) {
                $users = DB::table('old_users')
                            ->where('email', $email)
                            ->orderBy('created_at')
                            ->orderBy('id') // Secondary sort by ID for consistent results
                            ->get();

                // Keep the first (oldest), delete the rest
                $usersToDelete = $users->skip(1);
                
                foreach ($usersToDelete as $user) {
                    $this->line("Deleting user ID {$user->id} with email {$user->email}");
                    
                    DB::table('old_users')->where('id', $user->id)->delete();
                    $deletedCount++;
                }
            }
        });

        $this->info("Deleted {$deletedCount} duplicate users from old_users table.");
        $this->info("Backup table created: {$backupTable}");
    }

    private function showDuplicateStats()
    {
        $totalRecords = DB::table('old_users')->count();
        $uniqueEmails = DB::table('old_users')->distinct('email')->count();
        $duplicateCount = $totalRecords - $uniqueEmails;
        
        $this->info("Statistics:");
        $this->line("Total records: {$totalRecords}");
        $this->line("Unique emails: {$uniqueEmails}");
        $this->line("Duplicate records: {$duplicateCount}");
    }
}