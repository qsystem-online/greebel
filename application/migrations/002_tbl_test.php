<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Tbl_test extends CI_Migration {
	public function up(){
		$this->dbforge->drop_column('blog', 'blog_description');
	}

	public function down(){
		$this->dbforge->add_column('blog', array(
			'blog_description' => array(
				'type' => 'TEXT',
				'null' => TRUE,
			)
		));
	}
}