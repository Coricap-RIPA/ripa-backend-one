<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Notifications e-mail RIPA (ex. décision KYB).
 * En production : définir RIPA_MAIL_FROM, et idéalement SMTP via variables d’environnement / override CI email.php.
 */
$config['ripa_mail_enabled'] = getenv('RIPA_MAIL_ENABLED') === '0' || getenv('RIPA_MAIL_ENABLED') === 'false' ? false : true;
$config['ripa_mail_from_email'] = getenv('RIPA_MAIL_FROM') ?: 'noreply@localhost';
$config['ripa_mail_from_name'] = getenv('RIPA_MAIL_FROM_NAME') ?: 'RIPA';
