<?php

use App\Ship\Parents\Models\TemplateInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('templates', static function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                TemplateInterface::BASE_TYPE,
                TemplateInterface::JS_TYPE,
                TemplateInterface::CSS_TYPE,
                TemplateInterface::MENU_TYPE,
                TemplateInterface::PAGE_TYPE,
                TemplateInterface::WIDGET_TYPE,
            ])->default(TemplateInterface::PAGE_TYPE);
            $table->string('name');
            $table->bigInteger('theme_id')->unsigned()->index('INDEX_templates_themes');
            $table->bigInteger('page_id')->unsigned()->nullable()->index('INDEX_templates_pages');
            $table->bigInteger('child_page_id')->unsigned()->nullable()->index('INDEX_templates_child_pages');
            $table->bigInteger('language_id')->unsigned()->nullable()->index('INDEX_templates_languages');
            $table->bigInteger('parent_template_id')->unsigned()->nullable()->index('INDEX_templates_templates');
            $table->string('common_filepath')->nullable();
            $table->string('element_filepath')->nullable();
            $table->string('preview_filepath')->nullable();
            $table->timestamps();

            $table->foreign('theme_id', 'FK_templates_themes_foreign')
                ->references('id')
                ->on('themes')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('page_id', 'FK_templates_pages_foreign')
                ->references('id')
                ->on('pages')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('child_page_id', 'FK_templates_child_pages_foreign')
                ->references('id')
                ->on('pages')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('language_id', 'FK_templates_languages_foreign')
                ->references('id')
                ->on('languages')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('parent_template_id', 'FK_templates_templates_foreign')
                ->references('id')
                ->on('templates')
                ->onUpdate('CASCADE')
                ->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
}
