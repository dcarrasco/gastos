<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class RestoreDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:restore-db {archivo? : Archivo con datos a restaurar}';

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

    protected function getBackupFiles(): array
    {
        return collect(scandir(storage_path($this->backupPath)))
            ->diff(['.', '..', '.gitignore'])
            ->map(fn($file) => Str::before($file, '.'))
            ->values()
            ->all();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (is_null($archivo = $this->argument('archivo'))) {
            $archivo = $this->choice('Elegir archivo', $this->getBackupFiles());
        }

        $sqlFile = storage_path("{$this->backupPath}/{$archivo}.sql");

        if (! file_exists($sqlFile)) {
            die("Archivo a restaurar no existe ($sqlFile)");
        }

        try {
            $file = collect(file($sqlFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))
                ->map(fn($linea) => DB::unprepared($linea));

            Log::info('Database restore exitoso');

            $this->info('Database restore exitoso (' . $file->count() . ' lineas procesadas).');
        } catch (\Exception $exception) {
            Log::error('Database restore con errores: ' . $exception);
        }
    }
}
