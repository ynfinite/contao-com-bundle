<?php

namespace Ynfinite\ContaoComBundle\Controller;

use Ynfinite\YnfiniteFilterModel;
use Ynfinite\YnfiniteFilterFieldsModel;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ContentController extends Controller {

	public function filterAction(Request $request){
		// Initialize contao
		$contaoFramework = $this->get('contao.framework');
		$contaoFramework->initialize();
		$this->config = $contaoFramework->getAdapter(Config::class);

		// Get service
		$loadDataService = $this->get("ynfinite.contao-com.listener.communication");

		// Get request 
		$requestContent = json_decode($request->getContent());

		// Get filter
		$filter = YnfiniteFilterModel::findById($requestContent->filterId);
		$filterFields = YnfiniteFilterFieldsModel::findByPid($filter->id);

		// Build sort
		$sort = array("fields" => unserialize($filter->sortFields), "direction" => $filter->sortDirection);
		if($requestContent->sort) {
			$sort["fields"] = $requestContent->sort->fields;
			$sort["direction"] = $requestContent->sort->direction;
		}

		// Build skip / perPage
		$page = $requestContent->pagination->page;
		$perPage = $requestContent->pagination->perPage;
		
		$skip = ($page-1) * $perPage;

        $filterData = $loadDataService->buildFilterData($filterFields, $sort, $skip, $perPage, $requestContent->values);

	}
}