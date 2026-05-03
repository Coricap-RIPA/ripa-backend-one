<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('ripa_send_html_mail')) {
    /**
     * Envoie un e-mail HTML (CodeIgniter Email).
     *
     * @param string $to destinataire
     * @param string $subject sujet
     * @param string $html corps HTML
     * @return bool true si envoyé ou si envoi désactivé (log uniquement)
     */
    function ripa_send_html_mail($to, $subject, $html) {
        $to = trim((string) $to);
        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            log_message('error', 'ripa_send_html_mail: destinataire invalide');
            return false;
        }
        $CI =& get_instance();
        $CI->config->load('ripa_mail', true);
        $enabled = $CI->config->item('ripa_mail_enabled', 'ripa_mail');
        if ($enabled === false) {
            log_message('info', 'ripa_send_html_mail (désactivé): ' . $subject . ' → ' . $to);
            return true;
        }
        $from = (string) $CI->config->item('ripa_mail_from_email', 'ripa_mail');
        $from_name = (string) $CI->config->item('ripa_mail_from_name', 'ripa_mail');
        if ($from === '') {
            $from = 'noreply@localhost';
        }
        $CI->load->library('email');
        $CI->email->clear(true);
        $CI->email->initialize(array('mailtype' => 'html', 'charset' => 'utf-8', 'wordwrap' => true));
        $CI->email->from($from, $from_name);
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($html);
        $ok = $CI->email->send();
        if (!$ok) {
            log_message('error', 'ripa_send_html_mail échec: ' . $CI->email->print_debugger(array('headers')));
        }
        return $ok;
    }
}
