<?php

declare(strict_types=1);

namespace OCA\WifiPassword\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCA\WifiPassword\AppInfo\Application;
use OCP\Util;

class PageController extends Controller {
	public function __construct(string $appName,
								IRequest $request) {
		parent::__construct($appName, $request);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index(): TemplateResponse {
        Util::addScript(Application::APP_ID, "assets/page-main");
		return new TemplateResponse(Application::APP_ID, "page-main");
	}
}
