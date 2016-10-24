<?php

use yii\db\Migration;

/**
 * Handles adding imageFile_column to table `user`.
 */
class m161023_124515_add_imageFile_column_to_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'imageFile', $this->string(50));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'imageFile');
    }
}
