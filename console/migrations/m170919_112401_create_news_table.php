<?php

use yii\db\Migration;

/**
 * Handles the creation of table `news`.
 */
class m170919_112401_create_news_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'description' => $this->text(),
            'count_files' => $this->integer(2),
            'status' => $this->integer(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('news');
    }
}
