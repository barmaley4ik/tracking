<?php
namespace App\Http\Traits;


trait MsgTrait {

	public function msgInsert() {

        return 'Entry added';
    }

	public function msgUpdate() {

        return 'Entry updated';
    }

	public function msgDelete() {

        return 'Entry deleted';
    }

	public function msgRestore() {

        return 'Entry restored';
    }

	public function msgCantDelete() {

        return 'The entry cannot be deleted';
    }

	public function msgError() {

        return 'Error!';
    }

	public function msgErrorAdminUser() {

        return 'Error! You cannot delete a user with Administrator rights';
    }
}
