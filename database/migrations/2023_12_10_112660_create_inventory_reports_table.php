<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->enum('type', ['input','output']);
            $table->string('person')->comment('طرف حساب');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('guarantee_id')->nullable();
            $table->longText('description')->nullable();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate()->comment('تاریخ ورود/خروج');
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('guarantee_id')->references('id')->on('guarantees')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_reports');
    }
}
