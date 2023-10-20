<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allTables = $this->allTables();

        foreach ($allTables as $table) {
            if (!Schema::hasColumn($table, "school_id")) {
                Schema::table($table, function (Blueprint $table) {
                    $table->unsignedBigInteger("school_id")->nullable();
                });
            }
        }
    }

    public function allTables()
    {
        return [
            "exams",
            "categories",
            "exam_sessions",
            "exam_session_groups",
            "landings",
            "questions",
            "question_groups",
            "users"
        ];
    }
}
