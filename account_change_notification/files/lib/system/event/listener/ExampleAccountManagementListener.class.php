<?php

  namespace wcf\system\event\listener;
  use wcf\system\mail\Mail;
  use wcf\system\WCF;
  use wcf\util\DateUtil;

  /**
   * Listens to changes on the account management form of a user.
   *
   * @author  Felix Beuster
   * @copyright Felix Beuster 2017
   * @license MIT
   * @package Account Change Notification
   */
  class ExampleAccountManagementListener implements IParameterizedEventListener {

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
     * Extract changes from the event object.
     *
     * @param object    $usermail
     */
    private function getChanges($eventObj) {
      $changes = array();
      $session = WCF::getSession();
      $user    = WCF::getUser();

      # mail change
      if ($session->getPermission('user.profile.canChangeEmail') &&
          $eventObj->email != $user->email &&
          $eventObj->email != $user->newEmail) {
        $changes[] = 'mail';
      }

      # name change
      if ($session->getPermission('user.profile.canRename') &&
          $eventObj->username != $user->username) {
        $changes[] = 'name';
      }

      # password change
      if (!$user->authData) {
        if (!empty($eventObj->newPassword) ||
            !empty($eventObj->confirmNewPassword)) {
          $changes[] = 'password';
        }
      }

      # quit
      if ($session->getPermission('user.profile.canQuit')) {
        if (!$user->quitStarted &&
            $eventObj->quit == 1) {
          $date = DateUtil::getDateTimeByTimestamp($eventObj->quitStarted + 7 * 86400);
          $date->setTimezone($user->getTimeZone());

          $changes[] = array('quit_start', DateUtil::format($date));

        } else if ( $user->quitStarted &&
                    $eventObj->cancelQuit == 1) {
          $changes[] = 'quit_cancel';
        }
      }

      return $changes;
    }

    /**
     * @see IForm::saved()
     */
    protected function saved($eventObj) {
      $changes    = $this->getChanges($eventObj);

      if (count($changes)) {
        $this->sendMail($changes);
      }
    }

    /**
     * Sends a notification mail to the user,
     * informing about recent changes.
     *
     * @param array    $changes
     */
    private function sendMail($changes) {
      $lang       = WCF::getLanguage();
      $site       = $lang->get('example.accountChange.mail.site');

      $attention  = $lang->get('example.accountChange.mail.attention');
      $footer     = $lang->get('example.accountChange.mail.footer');
      $greeting   = $lang->getDynamicVariable(
                      'example.accountChange.mail.greeting',
                      array('user' => WCF::getUser()));
      $intro      = $lang->get('example.accountChange.mail.change');
      $mail_link  = '<a href="mailto:mail@example.com">mail@example.com</a>';
      $note       = $lang->get('example.accountChange.mail.note');
      $regards    = $lang->getDynamicVariable(
                      'example.accountChange.mail.regards',
                      array('site' => $site));
      $site_link  = '<a href="https://example.com">'.$site.'</a>';
      $subject    = $lang->get('example.accountChange.mail.subject');

      $message = '';
      $message .= "<!DOCTYPE html>\r";
      $message .= "<html>\r";
      $message .= " <head>\r";
      $message .= "  <title>$subject</title>\r";
      $message .= " <style>\r";
      $message .= "  /* Your custom styles would go here. */\r";
      $message .= " </style>\r";
      $message .= " </head>\r";
      $message .= " <body>\r";
      $message .= "  <h1>$attention</h1>\r";
      $message .= "  <p>$greeting</p>\r";
      $message .= "  <p>$intro</p>\r";
      $message .= "  <ul>\r";

      foreach ($changes as $change) {
        if (is_array($change) && count($change) === 2) {
          $message .= "   <li>".
                      $lang->getDynamicVariable(
                        'example.accountChange.mail.change.'.$change[0],
                        array('data' => $change[1])).
                      "</li>\r";

        } else {
          $message .= "   <li>".
                      $lang->get(
                        'example.accountChange.mail.change.'.$change).
                      "</li>\r";
        }
      }

      $message .= "  </ul>\r";
      $message .= "  <p>$note $mail_link</p>\r";
      $message .= "  <p>$regards</p>\r";
      $message .= "  <footer>$site_link $footer</footer>\r";
      $message .= " </body>\r";
      $message .= "</html>\r";

      $mail = new Mail( WCF::getUser()->__get('email'),
                        $subject, $message);
      $mail->setContentType('text/html');
      $mail->send();
    }
  }
