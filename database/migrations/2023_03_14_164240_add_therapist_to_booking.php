<?php

use App\Models\Role;
use App\Models\User;
use App\SD\SD;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admin = User::where('role_id', Role::where('role', SD::admin)->first()->id)
            ->first();

        Schema::table('bookings', function (Blueprint $table) use ($admin) {
            $table->foreignId('therapist_id')->default($admin->id);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
