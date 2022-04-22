<?php

declare(strict_types=1);

namespace OCA\WifiPassword\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Util;
use OCA\WifiPassword\AppInfo\Application;

class PageController extends Controller {
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index(): TemplateResponse
	{
		Util::addScript(Application::APP_ID, 'page-main');
		return new TemplateResponse(Application::APP_ID, "page-main");
	}
}
