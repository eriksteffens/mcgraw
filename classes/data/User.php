<?php

/**
 * Provides structure to the information from  get account info.
 * Used during login verification
 *
 * @author erik
 */
class User extends \mcs\FMRecordTemplate
{

    public $userName;
    public $userID;
    public $firstName;
    public $lastName;
    public $salt;
    public $hash;
    public $access;

	protected function readFields()
	{
		$this->userName = $this->getField('username');
		$this->userID = $this->getField('__pk_user_id');
		$this->firstName = $this->getField('name_first');
		$this->lastName = $this->getField('name_last');
		$this->salt = $this->getField('salt');
		$this->hash = $this->getField('hash');
		$this->access = $this->getField('access');
	}
	
	public function toArray(){
	    return [
		"userName"=>$this->userName,
		"userID"=>$this->userID,
		"firstName"=>$this->firstName,
		"lastName"=>$this->lastName,
		"salt"=>$this->salt,
		"hash"=>$this->hash,
		"access"=>$this->access
	    ];
	}
	
//	public function commitToRecord(){
//	    //$this->setField('user', $this->user);
//	    $this->commit();
//	}

}
