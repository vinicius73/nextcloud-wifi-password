<?php

declare(strict_types=1);

namespace OCA\WifiPassword\Controller;

use OCA\WifiPassword\AppInfo\Application;
use OCA\WifiPassword\Service\FileService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

class FileController extends Controller {
	private FileService $fileService;
	
	public function __construct(IRequest $request,
								FileService $service) {
		parent::__construct(Application::APP_ID, $request);
		$this->fileService = $service;
	}

	/**
	 * @CORS
	 *
	 * @NoCSRFRequired
	 *
	 * @NoAdminRequired
	 */
	public function index(): DataResponse {
		return new DataResponse(
			$this->fileService->findAll()
		);
	}

	/**
	 * @CORS
	 *
	 * @NoCSRFRequired
	 *
	 * @NoAdminRequired
	 */
	public function show($id): JSONResponse {
		return new JSONResponse(
			$this->fileService->getBySsid($id),
			Http::STATUS_OK
		);
	}

	/**
	 * @CORS
	 *
	 * @NoCSRFRequired
	 *
	 * @NoAdminRequired
	 */
	public function create(string $ssid, ?string $password = null): JSONResponse {
		return new JSONResponse(
			$this->fileService->create($ssid, $password),
			Http::STATUS_OK
		);
	}

	/**
	 * @CORS
	 *
	 * @NoCSRFRequired
	 *
	 * @NoAdminRequired
	 */
	public function update(string $id, string $ssid, ?string $password = null): JSONResponse {
		return new JSONResponse(
			$this->fileService->update($id, [
				'ssid' => $ssid,
				'password' => $password,
			]),
			Http::STATUS_OK
		);
	}

	/**
	 * @CORS
	 * @NoCSRFRequired
	 * @NoAdminRequired
	 */
	public function destroy($fileId): void {
		$this->fileService->delete($fileId);
	}
}
