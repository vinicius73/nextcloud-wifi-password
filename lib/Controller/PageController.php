<?php

declare(strict_types=1);

namespace OCA\WifiPassword\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Files\IRootFolder;
use OCP\IRequest;
use OCA\WifiPassword\AppInfo\Application;
use OCP\IUserSession;

class PageController extends Controller {
	protected IRootFolder $rootFolder;
	protected IUserSession $user;

	public function __construct(string $appName,
								IRequest $request) {
		parent::__construct($appName, $request);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index(): TemplateResponse {
		return new TemplateResponse(Application::APP_ID, "page-main");
	}
}
