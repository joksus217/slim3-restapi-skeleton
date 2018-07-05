<?php

namespace App\Models;


Class Model {
	public function __construct(){
        \ORM::configure(array(
            'connection_string' => 'mysql:host='.getConfig()['database']['host'].';dbname='.getConfig()['database']['database'],
            'username' => getConfig()['database']['username'],
            'password' => getConfig()['database']['password'],
            'logging' => getConfig()['database']['logging'],
            'id_column_overrides' => array(
                                    'blast_lists' => 'listid',
                                    'blast_users' => 'userid',
                                    'blast_usergroups' => 'groupid',
                                    'blast_newsletters' => 'newsletterid'
                                )
        ));
	}

    function resetSequence($seq='', $newid=0)
	{
		if (!$seq) {
			return false;
		}

		$newid = (int)$newid;
		if ($newid <= 0) {
			return false;
		}

        $result = \ORM::raw_execute('TRUNCATE TABLE '.$seq);

		if (!$result) {
			return false;
		}
        
        $result = \ORM::raw_execute('INSERT INTO '.$seq.' VALUES (:newid)', [':newid' => $newid]);
		
		if (!$result) {
			return false;
		}

		return $this->CheckSequence($seq);
    }

    function checkSequence($seq='')
	{
		if (!$seq) {
			return false;
        }
        
        $row = \ORM::for_table($seq)
							->raw_query('
                                    SELECT COUNT(*) AS count FROM '.$seq.'
                                ')->findOne();
		
		if ($row->count == 1) {
			return true;
		}
		return false;
    }
    
    
    
}