<?php

declare(strict_types=1);

namespace OCA\WifiPassword\Service;

use Exception;
use OCP\Files\Folder;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\IUserSession;

class FileService {
	private IRootFolder $rootFolder;
	private IUserSession $userSession;
	public function __construct(
		IRootFolder $rootFolder,
		IUserSession $userSession
	) {
		$this->rootFolder = $rootFolder;
		$this->userSession = $userSession;
	}

	public function findAll(): array {
		$return = [];
		$list = $this->getRootFolder()->getDirectoryListing();
		foreach ($list as $wifi) {
			$return[] = [
				'ssid' => $wifi->getName()
			];
		}
		return $return;
	}

	public function getBySsid($name): array {
		try {
			$json = $this->getRootFolder()->get($name)->getContent();
			return json_decode($json, true);
		} catch (NotFoundException $e) {
		}
		return [];
	}

	/**
	 * @param null|string $password
	 */
	public function create(string $ssid, ?string $password): array {
		$content = [
			'ssid' => $ssid,
			'password' => $password,
		];
		$this->getRootFolder()->newFile($ssid, json_encode($content));
		return $content;
	}

	public function update(string $ssid, array $newData): array {
		$file = $this->getRootFolder()->get($ssid);
		if ($newData['ssid'] !== $ssid) {
			$newName = dirname($file->getPath()) . DIRECTORY_SEPARATOR . $newData['ssid'];
			$file->move($newName);
		}
		$file->putContent(json_encode($newData));
		return $newData;
	}

	public function delete($ssid): bool {
		try {
			$file = $this->getRootFolder()->get($ssid);
			$file->delete();
			return true;
		} catch (NotFoundException $e) {
		}
		return false;
	}

	/**
	 * @return Folder
	 * @throws Exception
	 */
	protected function getRootFolder(): Folder {
		$userFolder = $this->rootFolder->getUserFolder($this->userSession->getUser()->getUID());
		try {
			$res = $userFolder->get(".WIFI");

			if ($res instanceof Folder) {
				return $res;
			}

			throw new Exception(".WIFI isn't a folder.");
		} catch (NotFoundException $err) {
			return $userFolder->newFolder(".WIFI");
		}
	}
}
