<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_list_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 20)->default('member');
            $table->timestamps();

            $table->unique(['task_list_id', 'user_id']);
        });

        // Pemilik daftar yang sudah ada ikut tercatat sebagai member pivot.
        foreach (DB::table('task_lists')->select('id', 'user_id')->get() as $taskList) {
            DB::table('task_list_user')->updateOrInsert(
                ['task_list_id' => $taskList->id, 'user_id' => $taskList->user_id],
                ['role' => 'owner', 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_list_user');
    }
};
