<?php

declare(strict_types=1);

namespace OCA\WifiPassword\Controller;

use OC\Security\CSP\ContentSecurityPolicyNonceManager;
use OCP\App\IAppManager;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCA\WifiPassword\AppInfo\Application;

class PageController extends Controller {
	private IAppManager $appManager;
	private ContentSecurityPolicyNonceManager $nonceManager;

	public function __construct(string      $appName,
								IRequest    $request,
								IAppManager $appManager,
								ContentSecurityPolicyNonceManager $nonceManager) {
		parent::__construct($appName, $request);

		$this->appManager = $appManager;
		$this->nonceManager = $nonceManager;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index(): TemplateResponse {
		$nonce = $this->nonceManager->getNonce();
		$base = $this->appManager->getAppWebPath(Application::APP_ID);
		$script = $base . '/js/assets/page-main.js';
		$style = $base . '/js/assets/page-main.css';

		return new TemplateResponse(Application::APP_ID, "page-main", compact('script', 'nonce', 'style'));
	}
}
