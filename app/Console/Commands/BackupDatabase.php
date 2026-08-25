<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'database:backup';

    protected $description = 'Create a backup of the configured MySQL database';

    public function handle(): int
    {
        $directory = storage_path('app/backups');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = $directory.DIRECTORY_SEPARATOR.'cic_wifi_'.now()->format('Ymd_His').'.sql';
        $mysqldump = env('MYSQLDUMP_PATH', 'C:\\xampp\\mysql\\bin\\mysqldump.exe');
        $command = sprintf('"%s" --host=%s --port=%s --user=%s %s > "%s"', $mysqldump, env('DB_HOST', '127.0.0.1'), env('DB_PORT', '3306'), env('DB_USERNAME', 'root'), env('DB_DATABASE', 'cic_wifi'), $file);
        $exitCode = 0;
        system($command, $exitCode);

        if ($exitCode !== 0) {
            $this->error('Database backup failed.');

            return self::FAILURE;
        }

        $this->info('Backup created: '.basename($file));

        return self::SUCCESS;
    }
}
