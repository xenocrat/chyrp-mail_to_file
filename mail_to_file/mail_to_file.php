<?php
    class MailToFile extends Modules {
        static function __install() {
            $digest = MAIN_DIR.DIR."digest.txt.php";
            $output = "<?php header(\"Status: 403\"); exit(\"Access denied.\"); ?>\n".
                      "MIME-Version: 1.0\r\n".
                      "Content-Type: multipart/digest; boundary=\"---correspondence---\"\r\n".
                      "\r\n---correspondence---\r\n";

            if (!file_exists($digest) and !@file_put_contents($digest, $output))
                error(__("Error"),
                      _f("The digest file <em>%s</em> could not be created.", fix($digest), "mail_to_file"));
        }

        static function __uninstall($confirm) {
            if ($confirm)
                @unlink(MAIN_DIR.DIR."digest.txt.php");
        }

        public function send_mail($function) {
            return array('MailToFile', 'mail_digest');
        }

        public function mail_digest($to, $subject, $message, $headers) {
            $output = "\r\n".$headers."\r\n".
                      "To: ".$to."\r\n".
                      "Date: ".datetime()."\r\n".
                      "Subject: ".$subject."\r\n\r\n".
                      $message."\r\n\r\n".
                      "---correspondence---\r\n";

            if (@file_put_contents(MAIN_DIR.DIR."digest.txt.php", $output, FILE_APPEND))
                return true;
            else
                return false;
        }
    }
