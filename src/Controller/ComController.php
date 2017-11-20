<?php

namespace Ynfinite\ContaoComBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ComController êxtends Controller{
	
	public function helloworldAction(){
		return new Response(
			'<html><body>Hello World!</body></html>'
		);
	}
}