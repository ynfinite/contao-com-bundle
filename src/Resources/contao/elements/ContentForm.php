<?php
namespace Ynfinite;

class ContentForm extends \ContentElement {

	protected $strTemplate = 'ce_ynfinite_form';

    public function generate()
    {
        if (TL_MODE == 'BE')
        {
		    $retVal = "Ynfinit Form";
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
        $formId = $this->ynfinite_form_id;
        

        $form = \Ynfinite\YnfiniteFormModel::findById($formId);
        $fields = unserialize($form->formFields);
		$requestToken = $container->get('security.csrf.token_manager')->refreshToken($container->getParameter('contao.csrf_token_name'))->getValue();
        
        $formElements = $loadDataService->getContentTypeFields($form->leadType);

        $outputFields = array();

        foreach($formElements as $element) {
        	if(in_array($element->config->field_name, $fields)) {
        		$outputFields[] = $element;
        	}
        }

        $this->Template->requestToken = $requestToken;
        $this->Template->formId = $this->ynfinite_form_id;
        $this->Template->leadType = $form->leadType;
        $this->Template->fields = htmlspecialchars(json_encode($outputFields, JSON_FORCE_OBJECT), ENT_QUOTES, 'UTF-8');
    }
}