<?php

namespace Ynfinite\ContaoComBundle\Controller;

use Ynfinite\YnfiniteFormModel;

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

		$testArray = array(
			"bitte_senden_sie_mir_jeweils" => "2 OrangeCards",
		);

		$testParent = array(
			"e_mail" => "agentur@koenigspunkt.de",
			"firma" => "Königspunkt GmbH",
			"nachname" => "Claus",
			"ort" => "Köln",
			"plz" => "50677",
			"strasse" => "Sachsenring 85",
			"telefonnummer" => "017644234438", 
			"vorname" => "Markus"
		);

		$data[] = $loadDataService->debugIt($testArray, $testParent, "5a1c540e2f61c5001095077a");

		return new JsonResponse($data);
	}

	public function sendAction(Request $request) {
		// Intitalize contao framework. Needed to use Contao3 content e.g. Models
		$contaoFramework = $this->get('contao.framework');
		$contaoFramework->initialize();

		// Get data from request
		$requestContent = json_decode($request->getContent());
		$parentFieldsArray = array();
		$contentFields = array();

		// Load the ynfinite from
		$form = YnfiniteFormModel::findById($requestContent->formId);
		$realFieldNames = $requestContent->realFieldNames;

		// Build formarray
		foreach($requestContent->data as $key => $field) {
			$parentIndex = strpos($key, "__parent__");
			if( $parentIndex === 0) {
				$key = substr($key, $parentIndex+strlen("__parent__"));
				$parentFieldsArray[$key] = $field;
			}
			else {
				$contentFields[$key] = $field;
			}
		}

		$mailSuccess = false;

		if($form->sendDataViaEmail && $form->targetEmail) {
			$formData = array_merge($contentFields, $parentFieldsArray);
			$mailer = $this->get("mailer");
			$message = new \Swift_Message($form->title);
			$message->setFrom("ynfinite@koenigspunkt.de")
				->setTo($form->targetEmail)
				->setBody($this->renderView('@YnfiniteContaoCom/Emails/sendform.html.twig', array(
					"realFieldNames" => $realFieldNames,
					"formData" => $formData,
					"title" => $form->title
				)), 'text/html');
			$mailer->send($message);
			$mailSuccess = true;
		}


		$resultData = array();

		if($form->sendDataToYnfinite) {
			$loadDataService = $this->get("ynfinite.contao-com.listener.communication");
			$result = $loadDataService->sendLead($contentFields, $parentFieldsArray, $requestContent->leadType, $requestContent->appId);
			
			switch($result['info']['http_code']) {
				case 201:
					$resultData = array(
						"success" => true,
						"mailSuccess" => $mailSuccess,
						"data" => $result["result"], 
						"message" => $form->successText,
						"appId" => $requestContent->appId, 
						"status" => $result['info']['http_code']
					);
				break;
				case 0:

				break;
				default: 
					$resultData = array(
						"success" => false,
						"mailSuccess" => $mailSuccess,
						"data" => $result["result"], 
						"message" => $form->successText,
						"appId" => $requestContent->appId, 
						"status" => $result['info']['http_code']
					);
				break;
			}
		}
		else {
			$resultData = array("success" => true, "mailSuccess" => $mailSuccess, "status" => 200, "message" => $form->successText, "appId" => $requestContent->appId);
		}

		return new JsonResponse($resultData);
	}
}