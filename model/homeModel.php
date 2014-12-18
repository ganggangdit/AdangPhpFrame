<?php
/**
 * @模型测试
 */
class homeModel extends Model{
        function test(){
                echo "this is test homeModel";
        }

        function testResult(){
                $this->db->show_databases();
        }
}


