<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JiteraCreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:database {dbname} {connection?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $schemaName = Str::snake($this->argument('dbname'));
        $connection = $this->argument('connection') ?? 'pgsql';
        $connection = Str::lower($connection);
        if (! in_array($connection, ['mysql', 'pgsql'])) {
            $this->error("Database connection [$connection] not supported.");

            return 0;
        }
        // Temporarily set the database connection to null
        config(["database.connections.$connection.database" => null]);

        // Create the database
        if ($connection === 'mysql') {
            $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        } elseif ($connection === 'pgsql') {
            $hasDb = DB::connection($connection)->select("SELECT datname FROM pg_catalog.pg_database WHERE lower(datname) = '$schemaName'");
            if (count($hasDb) > 0) {
                $this->info("Database $schemaName created successfully on $connection.");

                return 0;
            }
            $query = "CREATE DATABASE $schemaName WITH ENCODING 'UTF8' TEMPLATE template0;";
        } else {
            $this->error("Database connection [$connection] not supported.");

            return 0;
        }

        DB::statement($query);

        // Set the database connection back to the new database
        config(["database.connections.$connection.database" => $schemaName]);

        $this->info("Database $schemaName created successfully on $connection.");

        return 1;
    }
}
