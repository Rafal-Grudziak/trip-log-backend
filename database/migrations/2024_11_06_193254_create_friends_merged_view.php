<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Staudenmeir\LaravelMergedRelations\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS friends_view');

        Schema::createMergeView(
            'friends_view',
            [(new User())->acceptedFriendsAsSender(), (new User())->acceptedFriendsAsReceiver()]
        );
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS friends_view');
    }

};
