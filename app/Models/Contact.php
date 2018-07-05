<?php

namespace App\Models;

use App\Models\Model;

Class Contact extends Model{

	public function create($data){		
		$contact = \ORM::for_table('blast_lists')->create();
		$contact->set($data);

		if($contact->save()){
			return $contact->id();
		} else {
			return false;
		}
	}

	public function getAll($type = null){
		$row = \ORM::for_table('blast_lists');

		if($type == 'array'){
			$r = $row->findArray();
		} else {
			$r = $row->findMany();
		}

		return $r;
	}

	public function getAllById($listid, $type = null){
		$row = \ORM::for_table('blast_lists')
						->whereIn('listid', $listid);
						
		if($type == 'array'){
			$r = $row->findArray();
		} else {
			$r = $row->findMany();
		}

		return $r;
	}

	public function getAllByUser($listid, $userid, $type = null){
		$row = \ORM::for_table('blast_lists')
						->whereIn('listid', $listid)
						->where('ownerid', $userid);
						
		if($type == 'array'){
			$r = $row->findArray();
		} else {
			$r = $row->findMany();
		}

		return $r;
	}

	public function getByUser($id, $type = null){
		$row = \ORM::for_table('blast_lists')->where('ownerid', $id);

		if($type == 'array'){
			$r = $row->findArray();
		} else {
			$r = $row->findMany();
		}

		return $r;
	}

	public function checkUserList($userid, $listid){
		$row = \ORM::for_table('blast_lists')
				->where('ownerid', $userid)
				->where('listid', $listid)
				->findOne();

		if($row){
			return true;
		} else {
			return false;
		}
	}

	public function updateSubscriberCount($listid){
		$row = \ORM::for_table('blast_lists')->where('listid', $listid)->findOne();

		if($row){
			$row->subscribecount = $row->subscribecount + 1;
			if($row->save()){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

}