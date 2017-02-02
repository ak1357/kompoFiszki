<?php

namespace App\Interfaces;

interface iComponent
{
	
	public function controller($uriParams);
    public function loadAssets();
	public function getErrors();
	
}