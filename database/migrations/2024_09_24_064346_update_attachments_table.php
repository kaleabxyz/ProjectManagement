<?php
// Migration file: update_attachments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAttachmentsTable extends Migration
{
    public function up()
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // To store who uploaded the file
            $table->string('caption')->nullable(); // Caption is optional
        });
    }

    public function down()
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('caption');
        });
    }
}

