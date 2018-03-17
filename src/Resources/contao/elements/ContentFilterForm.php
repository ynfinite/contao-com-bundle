<?php
namespace Ynfinite;

class ContentFilterForm extends \ContentElement {

	protected $strTemplate = 'ce_ynfinite_filter_form';

    public function generate()
    {
        if (TL_MODE == 'BE')
        {
		    $retVal = "Ynfinit Filter Form";
		    return $retVal;
        }
        return parent::generate();
    }

    protected function compile() {

        if (TL_MODE == 'BE') {
            $this->strTemplate = 'be_wildcard';
            $this->Template = new \BackendTemplate($this->strTemplate);
        }

        $container = \Contao\System::getContainer();

		$loadDataService = $container->get("ynfinite.contao-com.listener.communication");
        $filterId = $this->ynfinite_filter_form_id;
        

        $filter = \Ynfinite\YnfiniteFilterFormModel::findById($filterId);
        $filterFields = \Ynfinite\YnfiniteFilterFormFieldsModel::findByPid($filter->id, array("order" => "sorting"));

        $filterData = \Input::get("filter")[$filter->alias];
		
        $requestToken = $container->get('security.csrf.token_manager')->getToken($container->getParameter('contao.csrf_token_name'))->getValue();

        $outputFields = array();

        foreach($filterFields as $element) {
    		$outputFields[$element->contentTypeField] = $element;
        }

        if($filter->jumpTo) {
            $objPage = \Contao\PageModel::findWithDetails($filter->jumpTo);
            if($objPage) {
                $this->Template->jumpTo = $objPage->getFrontendUrl();
            }
        }

        if($this->ynfinite_new_window) {
            $this->Template->formTarget = "target='_blank'";
        }

        $this->Template->submitLabel = $filter->submitLabel;
        $this->Template->cssData = $this->cssID;
        $this->Template->requestToken = $requestToken;
        $this->Template->filterId = $filterId;
        $this->Template->alias = $filter->alias;
        $this->Template->contentType = $filter->contentType;
        $this->Template->fields = $outputFields;
        $this->Template->filterData = $filterData;
    }
}