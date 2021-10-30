<?php

namespace App\Security;

interface ISecurity
{
	public function hashPassword($password);
}
