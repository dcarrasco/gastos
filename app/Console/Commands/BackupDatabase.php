<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup Database';

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

        $today = now()->format('Y-m-d-');

        if (!is_dir(storage_path($this->backupPath))) {
            mkdir(storage_path($this->backupPath));
        }

        $this->process = new Process(sprintf(
            'mysqldump --compact --skip-add-drop-table --skip-add-locks --no-create-info --ignore-table=gastos.acl_app --ignore-table=gastos.acl_modulo --ignore-table=gastos.acl_rol --ignore-table=gastos.acl_rol_modulo --ignore-table=gastos.acl_usuario_rol --ignore-table=gastos.acl_usuarios --ignore-table=gastos.migrations --user=%s --password=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path("{$this->backupPath}/{$today}.sql")
        ));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->process->mustRun();
            Log::info('Database backup exitoso');
        } catch (ProcessFailedExeption $exception) {
            Log::error('Database backup con errores', $exception);
        }
    }
}
