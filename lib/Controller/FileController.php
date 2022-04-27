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
	 * @NoAdminRequired
	 */
	public function index(): DataResponse {
		return new DataResponse(
			$this->fileService->findAll()
		);
	}

	/**
	 * @NoAdminRequired
	 */
	public function show($id): JSONResponse {
		$file = $this->fileService->getBySsid($id);
		if ($file) {
			return new JSONResponse($file, Http::STATUS_OK);
		}
		return new JSONResponse([], Http::STATUS_NOT_FOUND);
	}

	/**
	 * @NoAdminRequired
	 */
	public function create(string $ssid, ?string $password = null): JSONResponse {
		return new JSONResponse(
			$this->fileService->create($ssid, $password),
			Http::STATUS_OK
		);
	}

	/**
	 * @NoAdminRequired
	 */
	public function update(string $id, string $ssid, ?string $password = null): JSONResponse {
		$return = $this->fileService->update($id, [
			'ssid' => $ssid,
			'password' => $password,
		]);
		return new JSONResponse($return, Http::STATUS_OK);
	}

	/**
	 * @NoAdminRequired
	 */
	public function destroy($id): JSONResponse {
		$success = $this->fileService->delete($id);
		return new JSONResponse([], $success ? Http::STATUS_OK : Http::STATUS_NOT_FOUND);
	}
}
