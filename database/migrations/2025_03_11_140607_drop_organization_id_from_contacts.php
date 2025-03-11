<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old table if it exists from a previous failed migration
        Schema::dropIfExists('contacts_old');
        
        // For SQLite, we need to recreate the table without the column
        // First, create a backup of the table
        Schema::rename('contacts', 'contacts_old');

        // Drop the index if it exists
        try {
            DB::statement('DROP INDEX contacts_account_id_index');
        } catch (\Exception $e) {
            // Index might not exist, ignore the error
        }

        // Create a new table without the organization_id column
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->string('first_name', 25);
            $table->string('middle_name', 25);
            $table->string('last_name', 25);
            $table->string('email', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('region', 50)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('postal_code', 25)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Copy data from old table to new table, handling the case where middle_name might not exist
        DB::statement("INSERT INTO contacts (id, account_id, first_name, middle_name, last_name, email, phone, address, city, region, country, postal_code, created_at, updated_at, deleted_at) 
                      SELECT id, account_id, first_name, COALESCE(middle_name, '') as middle_name, last_name, email, phone, address, city, region, country, postal_code, created_at, updated_at, deleted_at FROM contacts_old");

        // Drop the old table
        Schema::dropIfExists('contacts_old');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->integer('organization_id')->nullable()->index()->after('account_id');
        });
    }
};
