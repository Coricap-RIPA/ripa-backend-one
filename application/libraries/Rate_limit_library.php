<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Rate limit pour l’API RIPA (Apiapp) — fenêtre fixe par IP et par palier.
 * Stockage : fichiers dans application/cache/rate_limit/ (pas de Redis requis).
 */
class Rate_limit_library {

    /** @var CI_Controller */
    protected $CI;

    /** @var string */
    protected $cache_dir;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('ripa_rate_limit', true);
        $this->cache_dir = APPPATH . 'cache/rate_limit/';
        if (!is_dir($this->cache_dir)) {
            if (!@mkdir($this->cache_dir, 0750, true) && !is_dir($this->cache_dir)) {
                log_message('error', 'Rate_limit: impossible de créer ' . $this->cache_dir);
            }
        }
    }

    /**
     * Vérifie le quota ; si dépassé, envoie 429 + Retry-After et termine la requête.
     */
    public function enforce() {
        if (!$this->CI->config->item('ripa_rate_limit_enabled', 'ripa_rate_limit')) {
            return;
        }

        $ip = $this->CI->input->ip_address();
        if ($ip === false || $ip === '') {
            $ip = 'unknown';
        }

        $bypass = $this->CI->config->item('ripa_rate_limit_bypass_ips', 'ripa_rate_limit');
        if (is_array($bypass) && in_array($ip, $bypass, true)) {
            return;
        }

        $router_method = $this->CI->router->method;
        $map = $this->CI->config->item('ripa_rate_limit_methods', 'ripa_rate_limit');
        if (!is_array($map)) {
            $map = array();
        }
        $tier = isset($map[$router_method]) ? $map[$router_method] : 'default';

        $limits = $this->CI->config->item('ripa_rate_limit', 'ripa_rate_limit');
        if (!is_array($limits) || !isset($limits[$tier])) {
            $tier = 'default';
        }
        if (!isset($limits[$tier])) {
            return;
        }

        $max = (int) $limits[$tier]['max'];
        $period = (int) $limits[$tier]['period'];
        if ($max < 1 || $period < 1) {
            return;
        }

        $window = (int) floor(time() / $period);
        $file_key = hash('sha256', $ip . '|' . $tier . '|' . $window);
        $path = $this->cache_dir . $file_key . '.cnt';

        $count = 0;
        if (is_file($path)) {
            $raw = @file_get_contents($path);
            $count = is_numeric($raw) ? (int) $raw : 0;
        }

        if ($count >= $max) {
            header('Retry-After: ' . $period);
            $this->CI->response_format->send_error(
                'Trop de requêtes vers l’API. Veuillez patienter avant de réessayer.',
                429
            );
        }

        $written = @file_put_contents($path, (string) ($count + 1), LOCK_EX);
        if ($written === false) {
            log_message('error', 'Rate_limit: écriture impossible ' . $path);
        }

        $this->maybe_cleanup_old_files();
    }

    /**
     * Nettoyage léger des fichiers expirés (fenêtre précédente), probabilité 2 % par requête.
     */
    protected function maybe_cleanup_old_files() {
        if (mt_rand(1, 50) !== 1) {
            return;
        }
        if (!is_dir($this->cache_dir) || !is_readable($this->cache_dir)) {
            return;
        }
        $ttl = 7200;
        $now = time();
        foreach (@scandir($this->cache_dir) as $f) {
            if ($f === '.' || $f === '..') {
                continue;
            }
            if (substr($f, -4) !== '.cnt') {
                continue;
            }
            $full = $this->cache_dir . $f;
            if (is_file($full) && ($now - @filemtime($full)) > $ttl) {
                @unlink($full);
            }
        }
    }
}
