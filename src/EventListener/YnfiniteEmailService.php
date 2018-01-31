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

	public function sendEMail($formData, $form, $templateData) {
			$fromEmail = $this->config->get("adminEmail");
			if(!$fromEmail) $fromEmail = "noreply@".$_SERVER['HTTP_HOST'];

			$title = $form->title;
			$targetMail = $form->targetEmail;
			
			$emailData = $this->pre_send_change_data($formData);
			$emailData = $this->pre_send_change_target($targetMail, $formData);

			$template = $this->getTemplate('contact-mail');

			$message = new \Swift_Message($title);
			$message->setFrom($fromEmail)
				->setTo($targetMail)
				->setBody($this->templating->render($template, $templateData), 'text/html');

			$this->mailer->send($message);

			return true;
	}

	public function pre_send_change_data($emailData) {
		return $emailData;
	}

	public function pre_send_change_target($target, $formData) {
		return $target;
	}

	public function getTemplate($name) {
		switch($name) {
			case "contact-mail":
				return '@YnfiniteContaoCom/Emails/sendform.html.twig';
				break;
			default: 
				return '@YnfiniteContaoCom/Emails/sendform.html.twig';
				break;
		}
	}
}