<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWelcomeFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hashed single-use token backing the "Sign in" button in the
            // welcome email. Cleared the moment the link is used.
            $table->string('welcome_token', 64)->nullable()->after('password');

            // Set while the account is still on the password the admin typed.
            $table->boolean('must_change_password')->default(false)->after('welcome_token');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['welcome_token', 'must_change_password']);
        });
    }
}
