<?php

declare(strict_types=1);

namespace OCA\WifiPassword\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Files\IRootFolder;
use OCP\IRequest;
use OCP\Files\NotFoundException;
use OCA\WifiPassword\AppInfo\Application;
use OCP\Files\Folder;
use Exception;

class PageController extends Controller {

	protected IRootFolder $rootFolder;

	public function __construct(string $appName, IRequest $request, IRootFolder $rootFolder) {
		parent::__construct($appName, $request);

		$this->rootFolder = $rootFolder;
	}
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index(): TemplateResponse
	{
		$list = $this->getListOfFiles();

		return new TemplateResponse(Application::APP_ID, "page-main", compact($list));
	}

	/**
	 * @return Folder
	 * @throws Exception
	 */
	protected function getRootFolder (): Folder
	{
		try {
			$res = $this->rootFolder->get(".WIFI");

			if ($res instanceof Folder) {
				return $res;
			}

			throw new Exception(".WIFI isn't a folder.");
		} catch (NotFoundException $err) {
			return $this->rootFolder->newFolder(".WIFI");
		}
	}

	/**
	 * @throws Exception
	 */
	protected function getListOfFiles(): array
	{
		return $this->getRootFolder()->getDirectoryListing();
	}
}
