<?php

namespace Ynfinite\ContaoComBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class LeadController extends Controller{
	
	public function indexAction(Request $request){

		$requestContent = json_decode($request->getContent());

		
		$loadDataService = $this->get("ynfinite.contao-com.listener.communication");
		$data = array();
		$data[] = "Trying to load data from service";
		$data[] = json_encode($requestContent);
		$data[] = $loadDataService->debugIt($requestContent->data, $requestContent->leadType);
		

		return new JsonResponse($data);
	}

	public function sendAction(Request $request) {
		
		$requestContent = json_decode($request->getContent());		
		$loadDataService = $this->get("ynfinite.contao-com.listener.communication");
		$result = $loadDataService->sendLead($requestContent->data, $requestContent->leadType, $requestContent->appId);
		
		return new JsonResponse($result);
	}
}