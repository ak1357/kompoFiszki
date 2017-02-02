<?php

namespace App\Interfaces;

interface iUser extends iComponent
{
	
	public function getUserId();
	public function isAdmin();
	
}