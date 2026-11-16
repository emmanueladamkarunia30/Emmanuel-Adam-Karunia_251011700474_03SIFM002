<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique()->after('name');
        });

        // Isi username akun yang sudah ada, diambil dari bagian depan email
        $used = [];
        foreach (DB::table('users')->orderBy('id')->get() as $u) {
            $base = strtolower(preg_replace('/[^A-Za-z0-9_.-]/', '', (string) strstr($u->email, '@', true)));
            $base = $base !== '' ? $base : 'user' . $u->id;

            $name = $base;
            $i = 1;
            while (in_array($name, $used, true)) {
                $name = $base . $i++;
            }
            $used[] = $name;

            DB::table('users')->where('id', $u->id)->update(['username' => $name]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};