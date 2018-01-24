<?php

namespace Ynfinite;

class CheckArticle {

	public function checkVisibility($objTemplate, $arrData, $objModule) {
		if($arrData['hideTroughYnfinite']) {
			$container = \Contao\System::getContainer();

	        $contentId = null;
	        $content = null;

	        $loadDataService = $container->get("ynfinite.contao-com.listener.communication");

	        if(!$arrData['ynfinite_content_id']) {
	            $contentId = \Input::get('items');
	            if(!$contentId) {
	                $basename = pathinfo($_SERVER['REQUEST_URI'])['basename'];
	                $contentId = basename($basename, ".html");
	            }

	            if($contentId) {
	                $content = $loadDataService->getContent($contentId, $arrData['ynfinite_contentType']);    
	            }                            
	        }
	        else {
	            $content = $loadDataService->getContentById($arrData['ynfinite_content_id'], $arrData['ynfinite_contentType']);
	        }

	        if($content) {

	            if($arrData['ynfinite_publish_field']) {
	                $publishField = $arrData['ynfinite_publish_field'];
	                $objTemplate->ynfinite_show = $content->content->$publishField;
	            }
	        }
		}
	}
}