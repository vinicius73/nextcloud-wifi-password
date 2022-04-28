<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;
use Libresign\NextcloudBehat\NextcloudContext;
use PhpBuiltin\RunServerListener;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends NextcloudContext {

	/**
	 * @When user :user send ssid with (:statusCode)
	 */
	public function userSendSsidWith(string $user, int $statusCode, TableNode $table): void {
		$this->setCurrentUser($user);
		$this->sendRequest('POST', '/apps/wifi_password/api/files', $table);
		$this->assertStatusCode($this->response, $statusCode);
	}

	/**
	 * @When user :user edit ssid :ssid with (:statusCode)
	 */
	public function userEditSsidWith(string $user, string $ssid, int $statusCode, TableNode $table): void {
		$this->setCurrentUser($user);
		$this->sendRequest('PUT', '/apps/wifi_password/api/files/' . $ssid, $table);
		$this->assertStatusCode($this->response, $statusCode);
	}

	/**
	 * @When user :user get ssid :ssid with (:statusCode)
	 */
	public function userGetSsidWith(string $user, string $ssid, int $statusCode): void {
		$this->setCurrentUser($user);
		$this->sendRequest('GET', '/apps/wifi_password/api/files/' . $ssid);
		$this->assertStatusCode($this->response, $statusCode);
	}

	/**
	 * @When user :user list ssid with (:statusCode)
	 */
	public function userListSsidWith(string $user, int $statusCode, ?TableNode $table = null): void {
		$this->setCurrentUser($user);
		$this->sendRequest('GET', '/apps/wifi_password/api/files');
		$this->assertStatusCode($this->response, $statusCode);


		$realResponseArray = json_decode($this->response->getBody()->getContents(), true);
		if ($table instanceof TableNode) {
			$expectedValues = $table->getColumnsHash();
			Assert::assertEqualsCanonicalizing($expectedValues, $realResponseArray);
		} else {
			Assert::assertEmpty($realResponseArray);
		}
	}

	/**
	 * @When user :user delete ssid :ssid with (:statusCode)
	 */
	public function userDeleteSsidWith(string $user, string $ssid, int $statusCode): void {
		$this->setCurrentUser($user);
		$this->sendRequest('DELETE', '/apps/wifi_password/api/files/' . $ssid);
		$this->assertStatusCode($this->response, $statusCode);
	}
}
