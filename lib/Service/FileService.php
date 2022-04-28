<?php

declare(strict_types=1);

namespace OCA\WifiPassword\Service;

use Exception;
use OCP\Files\Folder;
use OCP\Files\InvalidPathException;
use OCP\Files\IRootFolder;
use OCP\Files\Node;
use OCP\Files\NotFoundException;
use OCP\Files\NotPermittedException;
use OCP\IUserSession;
use OCP\Lock\LockedException;

class FileService {
	protected static string $FILE_SUFFIX = ".json.wifi";
	protected static string $ROOT_FOLDER = ".WIFI";

	private IRootFolder $rootFolder;
	private IUserSession $userSession;

	public function __construct(
		IRootFolder $rootFolder,
		IUserSession $userSession
	) {
		$this->rootFolder = $rootFolder;
		$this->userSession = $userSession;
	}

	/**
	 * @throws NotFoundException
	 * @throws Exception
	 */
	public function findAll(): array {
		$return = [];
		$list = $this->getRootFolder()->getDirectoryListing();
		foreach ($list as $wifi) {
			if (!str_ends_with($wifi->getName(), self::$FILE_SUFFIX)) {
				continue;
			}

			$return[] = [
				'ssid' => str_replace(self::$FILE_SUFFIX, "", $wifi->getName())
			];
		}
		return $return;
	}

	/**
	 * @throws Exception
	 */
	public function getBySsid(string $name): array {
		try {
			$json = $this->getFile($name)->getContent();
			return json_decode($json, true);
		} catch (NotFoundException $e) {
		}
		return [];
	}

	/**
	 * @param null|string $password
	 * @throws NotPermittedException
	 */
	public function create(string $ssid, string $type, ?string $password): array {
		$content = [
			'ssid' => $ssid,
			'password' => $password,
			'type' => $type
		];
		$this->getRootFolder()->newFile(self::buildFilename($ssid), json_encode($content));
		return $content;
	}

	/**
	 * @throws NotPermittedException
	 * @throws InvalidPathException
	 * @throws NotFoundException
	 * @throws LockedException
	 */
	public function update(string $ssid, array $newData): array {
		$file = $this->getFile($ssid);

		if ($newData['ssid'] !== $ssid) {
			$newName = dirname($file->getPath()) . DIRECTORY_SEPARATOR . self::buildFilename($newData['ssid']);
			$file->move($newName);
		}
		$file->putContent(json_encode($newData));
		return $newData;
	}

	/**
	 * @throws NotPermittedException
	 * @throws InvalidPathException
	 */
	public function delete(string $ssid): bool {
		try {
			$this->getFile($ssid)->delete();
			return true;
		} catch (NotFoundException $e) {
		}
		return false;
	}

	/**
	 * @throws NotFoundException
	 */
	protected function getFile(string $ssid): Node {
		return $this->getRootFolder()->get(self::buildFilename($ssid));
	}

	protected static function buildFilename(string $ssid): string {
		return $ssid . self::$FILE_SUFFIX;
	}

	/**
	 * @return Folder
	 * @throws Exception
	 */
	protected function getRootFolder(): Folder {
		$userFolder = $this->rootFolder->getUserFolder($this->userSession->getUser()->getUID());
		try {
			$res = $userFolder->get(self::$ROOT_FOLDER);

			if ($res instanceof Folder) {
				return $res;
			}

			throw new Exception(self::$ROOT_FOLDER . " isn't a folder.");
		} catch (NotFoundException $err) {
			return $userFolder->newFolder(self::$ROOT_FOLDER);
		}
	}
}
