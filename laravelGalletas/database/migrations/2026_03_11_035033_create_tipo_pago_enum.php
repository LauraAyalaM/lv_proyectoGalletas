<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TYPE tipo_pago_enum AS ENUM ('efectivo', 'nequi', 'transferencia', 'credito')");
    }

    public function down(): void
    {
        DB::statement("DROP TYPE tipo_pago_enum");
    }
};