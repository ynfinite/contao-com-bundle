<?php

namespace Ynfinite;

class CheckArticle {

	public function checkVisibility($objTemplate, $arrData, $objModule) {
		if($arrData['hideTroughYnfinite']) {
			$objTemplate->ynfinite_show = $this->doVisibilityCheck($arrData['ynfinite_content_id'], $arrData['ynfinite_contentType'], $arrData['ynfinite_publish_field']);
		}
	}

	public function doVisibilityCheck($contentId, $contentType, $publishField) {
		$container = \Contao\System::getContainer();

        $contentId = null;
        $content = null;

        $loadDataService = $container->get("ynfinite.contao-com.listener.communication");

        if(!$contentId) {
            $contentId = \Input::get('items');
            if(!$contentId) {
                $basename = pathinfo($_SERVER['REQUEST_URI'])['basename'];
                $contentId = basename($basename, ".html");
            }

            if($contentId) {
                $content = $loadDataService->getContent($contentId, $contentType);
            }                            
        }
        else {
            $content = $loadDataService->getContentById($contentId, $contentType);
        }

        if($content) {
            if($publishField) {
                return $content->content->$publishField;
            }
        }

        return false;
	}
}