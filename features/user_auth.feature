Feature:
  In order to prove that an user can log in to the plateform
  As a user
  I want to use my email and password

  @alice(user)
  Scenario:
    When I send a "POST" request to "/api/login" with:
      | email             | password |
      | user@example.com  | password |
    Then response should contains
      | token             | refresh_token |

  @alice(user)
  Scenario:
    Given I am authenticating as "user@example.com" with "password" password
    When I send a get request to "/api/users/me"
    Then response should contains
      | email              | isMe |
      | user@example.com   | 1    |
