<?php

/**
* This will be changed to a db based per user unique salt
*/

namespace App\Security\Impl;

use \App\Security\ISecurity;

class Hasher implements ISecurity
{
	public function hashPassword($password)
	{
		return hash('sha256', "ACOUÉAfÖZ+'RP(ÖRH" + $password + "FAfqawf");
	}
}
