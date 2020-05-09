<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class RestoreDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:restore-db {archivo : Archivo con datos a restaurar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore Database';

    protected $process;
    protected $backupPath = 'database_backup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (!is_dir(storage_path($this->backupPath))) {
            mkdir(storage_path($this->backupPath));
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sqlFile = storage_path("{$this->backupPath}/{$this->argument('archivo')}.sql");

        $this->process = Process::fromShellCommandline(sprintf(
            'mysql --user=%s -p%s --database=%s --host=%s < %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            config('database.connections.mysql.host'),
            $sqlFile
        ));

        if (! file_exists($sqlFile)) {
            die("Archivo a restaurar no existe ($sqlFile)");
        }

        try {
            $this->process->mustRun();
            Log::info('Database restore exitoso');
        } catch (ProcessFailedExeption $exception) {
            Log::error('Database restore con errores', $exception);
        }
    }
}
