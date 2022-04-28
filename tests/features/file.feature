Feature: Files
  Background:
    Given user "user1" exists

  Scenario: send file
    When user "user1" send ssid with (200)
      | ssid | ssid |
      | type | wpa  |
    And user "user1" get ssid "ssid" with (200)
    And the response should be a JSON array with the following mandatory values
      | key      | value  |
      | ssid     | "ssid" |
      | password | null   |
    And user "user1" list ssid with (200)
      | ssid |
      | ssid |
    And user "user1" edit ssid "ssid" with (200)
      | ssid | ssid2 |
      | type | wpa-2 |
    And user "user1" get ssid "ssid" with (404)
    And user "user1" get ssid "ssid2" with (200)
    And the response should be a JSON array with the following mandatory values
      | key      | value  |
      | ssid     | "ssid2" |
      | password | null   |
    And user "user1" delete ssid "ssid2" with (200)
    And user "user1" list ssid with (200)