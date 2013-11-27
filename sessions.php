<?php
/**
 * session_start (with sessions stored in files) is blocking in PHP, 
 * so this issue will appear if you try to start several server sessions for the same browser session (AJAX or multiple browser tabs/windows). 
 * Each session_start will wait until the other sessions have been closed.
 * See here: http://konrness.com/php5/how-to-prevent-blocking-php-requests/
 * Try changing from files to database storage of sessions.
 */
