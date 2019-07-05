<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddUuidExtensionToPostgresql extends Migration
{
    public function up()
    {
        if ('pgsql' !== DB::connection()->getDriverName()) {
            return;
        }

        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
    }

    public function down()
    {
        if ('pgsql' !== DB::connection()->getDriverName()) {
            return;
        }

        DB::statement('DROP EXTENSION IF EXISTS "uuid-ossp";');
    }
}
