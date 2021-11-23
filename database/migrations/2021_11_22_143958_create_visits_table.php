<?php

use App\Models\Patient;
use App\Models\Vital;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id(); 
            $table->foreignIdFor(Patient::class); 
            $table->foreignIdFor(Vital::class); 
            $table->string('general_health');
            $table->string('visit_date');
            $table->boolean('on_diet')->default(false);
            $table->boolean('on_drugs')->default(false);
            $table->text('comments'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visits');
    }
}
