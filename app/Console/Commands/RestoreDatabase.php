<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

    /** @var Process<mixed> */
    protected Process $process;

    protected string $backupPath = 'database_backup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (! is_dir(storage_path($this->backupPath))) {
            mkdir(storage_path($this->backupPath));
        }
    }

    /** @return string[] */
    protected function getBackupFiles(): array
    {
        if (! $files = scandir(storage_path($this->backupPath))) {
            $files = [];
        }

        return collect($files)
            ->diff(['.', '..', '.gitignore'])
            ->map(fn ($file) => Str::before($file, '.'))
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
            exit("Archivo a restaurar no existe ($sqlFile)");
        }

        try {
            if (! $file = file($sqlFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) {
                $file = [];
            }

            $file = collect($file)
                ->map(fn ($linea) => DB::unprepared($linea));

            Log::info('Database restore exitoso');

            $this->info('Database restore exitoso ('.$file->count().' lineas procesadas).');
        } catch (\Exception $exception) {
            Log::error('Database restore con errores: '.$exception);
        }
    }
}
