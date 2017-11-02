<?php

  namespace wcf\system\event\listener;
  use wcf\data\user\User;
  use wcf\system\WCF;

  /**
   * Listens to user registrations and forwards data
   * to create an account on a separate site.
   *
   * @author  Felix Beuster
   * @copyright Felix Beuster 2017
   * @license MIT
   * @package Example Registration Forward
   */
  class ExampleRegisterListener implements IParameterizedEventListener {

    /**
     * app secret that the targeted api can verify
     * @var string
     */
    private $app_secret = 'secret_string';

    /**
     * the API endpoint, that the request is sent to
     * @var string
     */
    private $target_url = 'https://example.de/api.php';


    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters) {
      if (method_exists($this, $eventName) &&
          $eventName !== 'execute') {
        $this->$eventName($eventObj);

      } else {
        throw new \LogicException('Unreachable');
      }
    }

    /**
     * @see IForm::saved()
     */
    protected function saved($eventObj) {
      $user             = User::getUserByEmail($eventObj->email);
      $forward_enabled  = $user->getUserOption('exampleRegisterForward');
      $language         = $user->getLanguage();

      if ($forward_enabled) {
        $this->register($eventObj->email,
                        $eventObj->username,
                        $eventObj->password,
                        $language->getFixedLanguageCode());
      }
    }

    /**
     * Sending the actual request to the defined target URL.
     *
     * @param string    $usermail
     * @param string    $username
     * @param string    $userpass
     * @param string    $language
     */
    private function register($usermail, $username, $userpass, $language) {
      # make a channel
      $channel = curl_init();

      # setup parameters
      $query = http_build_query(array('app_secret'  => $this->app_secret,
                                      'language'    => $language,
                                      'usermail'    => $usermail,
                                      'username'    => $username,
                                      'userpass'    => $userpass));

      # setup request
      curl_setopt($channel, CURLOPT_URL,            $this->target_url);
      curl_setopt($channel, CURLOPT_POST,           1);
      curl_setopt($channel, CURLOPT_POSTFIELDS,     $query);
      curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);

      # esecute query
      $server_output = curl_exec ($channel);

      # close channel
      curl_close ($channel);
    }
  }
