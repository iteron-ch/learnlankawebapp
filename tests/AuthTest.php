<?php

/**
 * This class is used for Unit testing of login module
 * @author     Icreon Tech  - dev1.
 */
class AuthTest extends TestCase {

    /**
     * This claas is used for Unit testing of Auth module
     * @author     Icreon Tech  - dev1.
     */
    protected $baseUrl = 'http://londonce.lan/';

    public function testLandingPage() {
        $this->visit('http://londonce.lan/login')
                ->see('log')
                ->see('password')
                ->see('Login');
    }

    /**
     * Test: View Login
     *
     * Description:
     * This will test that the user will be able to view the login page.
     *
     * @return void
     */
    public function testViewLogin() {
        $this->visit('http://londonce.lan/login')
                ->see('Login');
    }

    /**
     * Test: Username & Password Field Blank Validation
     *
     * Description:
     * This will test that the user has not entered a valid username and password.
     *
     * @return void
     */
    public function testLoginValidation() {
        $this->visit('http://londonce.lan/login')
                ->submitForm('Login', ['log' => '', 'password' => ''])
                ->see('Please enter a valid username.')
                ->see('Please enter a valid password.');
    }

    /**
     * Test: Username proper but Password Field Blank Validation
     *
     * Description:
     * This will test that the user has entered a valid username but password is blank.
     *
     * @return void
     */
    public function testLoginValidationLog() {
        $this->visit('http://londonce.lan/login')
                ->submitForm('Login', ['log' => 'aaaaa', 'password' => ''])
                ->see('Please enter a valid password.');
    }

    /**
     * Test: Username not proper but Password Field Validation
     *
     * Description:
     * This will test that the user has not entered a valid username but password is not blank.
     *
     * @return void
     */
    public function testLoginValidationPass() {
        $this->visit('http://londonce.lan/login')
                ->submitForm('Login', ['log' => '', 'password' => 'sfaff'])
                ->see('Please enter a valid username.');
    }

    /**
     * Test: Username Validation
     *
     * Description:
     * This will test that the user has entered a valid username into the username field.
     *
     * @return void
     */
    public function testEmailValidation() {
        $this->visit('http://londonce.lan/login')
                ->submitForm('Login', ['log' => 'dfdfdfd', 'password' => '11'])
                ->see('Your Username or Password has not been recognised. Please try again.');
    }

}
