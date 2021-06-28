Feature:
  In order to prove that an user fetch only authorized datas
  As a user
  I cant fetch an other user phone number

  @alice(user,user2)
  Scenario:
    Given I am authenticating as "user@example.com" with "password" password
    When I send a get request to "/api/users/{user2.id}"
    Then response should contains
      | email |
    Then response should not contains
      | phoneNumber |
  @alice(admin,user2)
  Scenario:
    Given I am authenticating as "admin@example.com" with "password" password
    When I send a get request to "/api/users/{user2.id}"
    Then response should contains
      | email | phoneNumber |
