<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;
use PhpBuiltin\RunServerListener;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context {
	public const TEST_PASSWORD = '123456';
	private string $baseUrl;
	private RunServerListener $server;
	private ?string $currentUser = null;
	private array $createdUsers = [];
	private ResponseInterface $response;
	/** @var CookieJar */
	private $cookieJars;
	public function __construct() {
		$this->server = RunServerListener::getInstance();
		$this->baseUrl = RunServerListener::getServerRoot();
		$this->cookieJars = [];
	}

	public function sendOCSRequest($verb, $url, $body = null, array $headers = []) {
		$url = 'ocs/v2.php' . $url;
		$this->sendRequest($verb, $url, $body, $headers);
	}

	/**
	 * @When /^sending "([^"]*)" to "([^"]*)" with$/
	 * @param string $verb
	 * @param string $url
	 * @param TableNode|array|null $body
	 * @param array $headers
	 */
	public function sendRequest($verb, $url, $body = null, array $headers = []) {
		$url = ltrim($url, '/');
		if (strpos($url, 'ocs/v2.php') === false) {
			$url = 'index.php/' . $url;
		}
		$fullUrl = $this->baseUrl . $url;
		$client = new Client();
		$options = ['cookies' => $this->getUserCookieJar($this->currentUser)];
		if ($this->currentUser === 'admin') {
			$options['auth'] = ['admin', 'admin'];
		} elseif (strpos($this->currentUser, 'guest') !== 0) {
			$options['auth'] = [$this->currentUser, self::TEST_PASSWORD];
		}
		if ($body instanceof TableNode) {
			$fd = $body->getRowsHash();
			$options['form_params'] = $fd;
		} elseif (is_array($body)) {
			$options['form_params'] = $body;
		}

		$options['headers'] = array_merge($headers, [
			'OCS-ApiRequest' => 'true',
			'Accept' => 'application/json',
		]);

		try {
			$this->response = $client->{$verb}($fullUrl, $options);
		} catch (ClientException $ex) {
			$this->response = $ex->getResponse();
		} catch (\GuzzleHttp\Exception\ServerException $ex) {
			$this->response = $ex->getResponse();
		}
	}

	protected function getUserCookieJar($user) {
		if (!isset($this->cookieJars[$user])) {
			$this->cookieJars[$user] = new CookieJar();
		}
		return $this->cookieJars[$user];
	}

	/**
	 * @Given /^as user "([^"]*)"$/
	 * @param string $user
	 */
	public function setCurrentUser($user) {
		$this->currentUser = $user;
	}

	/**
	 * @Given /^user "([^"]*)" exists$/
	 * @param string $user
	 */
	public function assureUserExists($user) {
		$response = $this->userExists($user);
		if ($response->getStatusCode() !== 200) {
			$this->createUser($user);
			// Set a display name different than the user ID to be able to
			// ensure in the tests that the right value was returned.
			$this->setUserDisplayName($user);
			$response = $this->userExists($user);
			$this->assertStatusCode($response, 200);
		}
	}

	private function userExists($user) {
		$currentUser = $this->currentUser;
		$this->setCurrentUser('admin');
		$this->sendOCSRequest('GET', '/cloud/users/' . $user);
		$this->setCurrentUser($currentUser);
		return $this->response;
	}

	private function createUser($user) {
		$currentUser = $this->currentUser;
		$this->setCurrentUser('admin');
		$this->sendOCSRequest('POST', '/cloud/users', [
			'userid' => $user,
			'password' => self::TEST_PASSWORD,
		]);
		$this->assertStatusCode($this->response, 200, 'Failed to create user');

		//Quick hack to login once with the current user
		$this->setCurrentUser($user);
		$this->sendOCSRequest('GET', '/cloud/users' . '/' . $user);
		$this->assertStatusCode($this->response, 200, 'Failed to do first login');

		$this->createdUsers[] = $user;

		$this->setCurrentUser($currentUser);
	}

	private function deleteUser($user) {
		$currentUser = $this->currentUser;
		$this->setCurrentUser('admin');
		$this->sendOCSRequest('DELETE', '/cloud/users/' . $user);
		$this->setCurrentUser($currentUser);

		unset($this->createdUsers[array_search($user, $this->createdUsers, true)]);

		return $this->response;
	}

	private function setUserDisplayName($user) {
		$currentUser = $this->currentUser;
		$this->setCurrentUser('admin');
		$this->sendOCSRequest('PUT', '/cloud/users/' . $user, [
			'key' => 'displayname',
			'value' => $user . '-displayname'
		]);
		$this->setCurrentUser($currentUser);
	}

	/**
	 * @param ResponseInterface $response
	 * @param int $statusCode
	 * @param string $message
	 */
	protected function assertStatusCode(ResponseInterface $response, int $statusCode, string $message = '') {
		Assert::assertEquals($statusCode, $response->getStatusCode(), $message);
	}

	/**
	 * @BeforeScenario
	 */
	public function setUp() {
		$this->createdUsers = [];
	}

	/**
	 * @AfterScenario
	 */
	public function tearDown() {
		foreach ($this->createdUsers as $user) {
			$this->deleteUser($user);
		}
	}

	/**
	 * @Then the response should have a status code :code
	 * @param string $code
	 * @throws InvalidArgumentException
	 */
	public function theResponseShouldHaveStatusCode($code) {
		$currentCode = $this->response->getStatusCode();
		Assert::assertEquals($code, $currentCode);
	}

	/**
	 * @Then the response should be a JSON array with the following mandatory values
	 * @param TableNode $table
	 * @throws InvalidArgumentException
	 */
	public function theResponseShouldBeAJsonArrayWithTheFollowingMandatoryValues(TableNode $table) {
		$this->response->getBody()->seek(0);
		$expectedValues = $table->getColumnsHash();
		$realResponseArray = json_decode($this->response->getBody()->getContents(), true);
		foreach ($expectedValues as $value) {
			$actualJson = json_encode($realResponseArray[$value['key']]);
			Assert::assertJsonStringEqualsJsonString($value['value'], $actualJson);
		}
	}

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