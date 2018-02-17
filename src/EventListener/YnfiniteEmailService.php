<?php

namespace Ynfinite\ContaoComBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Config;

use Ynfinite\YnfiniteCacheModel;

use \DateTime;

class YnfiniteEmailService {

	private $framework;
	private $config;
	private $mailer;
	private $templating;

	public function __construct(ContaoFrameworkInterface $framework, \Swift_Mailer $mailer, $templating) {
		$this->framework = $framework;
		$this->framework->initialize();

		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->config = $framework->getAdapter(Config::class);
	}	

	public function sendEMail($targetMail, $title, $templateName, $templateData) {
			$fromEmail = $this->config->get("adminEmail");
			if(!$fromEmail) $fromEmail = "noreply@".$_SERVER['HTTP_HOST'];
			
			$templateData = $this->pre_send_change_data($templateData);
			$targetMail = $this->pre_send_change_target($targetMail, $templateData);

			$message = new \Swift_Message($title);
			$message->setFrom($fromEmail)
				->setTo($targetMail)
				->setBody($this->templating->render($templateName, $templateData), 'text/html');

			$this->mailer->send($message);

			return true;
	}

	public function pre_send_change_data($templateData) {
		return $templateData;
	}

	public function pre_send_change_target($target, $templateData) {
		return $target;
	}
}