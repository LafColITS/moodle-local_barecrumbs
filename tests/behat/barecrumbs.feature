@local @local_barecrumbs
Feature: Hide Inactive Courses

  Background:
  Given the following "categories" exist:
    | name   | category | idnumber |
    | Parent | 0        | PCAT     |
    | Child  | PCAT     | CCAT     |
  Given the following "courses" exist:
    | fullname    | shortname  | category |
    | Test Course | testcourse | CCAT     |
  Given the following "users" exist:
    | username | firstname | lastname |
    | teacher  | Teacher   | Teacher  |
  Given the following "course enrolments" exist:
    | user    | course     | role           |
    | teacher | testcourse | editingteacher |

  @javascript
  Scenario: Admin (should still see links)
    When I log in as "admin"
    And I am on site homepage
    And I follow "Test Course"
    Then "//ol[@class='breadcrumb']//a[text()='Parent']" "xpath_element" should exist
    Then "//ol[@class='breadcrumb']//a[text()='Child']" "xpath_element" should exist

  @javascript
  Scenario: Teacher (should see plain text)
    When I log in as "teacher"
    And I am on site homepage
    And I follow "Test Course"
    Then "//ol[@class='breadcrumb']//a[text()='Parent']" "xpath_element" should not exist
    Then "//ol[@class='breadcrumb']//a[text()='Child']" "xpath_element" should not exist
