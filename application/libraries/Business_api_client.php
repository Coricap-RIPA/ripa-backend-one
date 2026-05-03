<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Client HTTP interne pour consommer l'API Business (api/business/*) depuis le portail web.
 * Le token JWT est stocké dans la session `business_api_jwt`.
 */
class Business_api_client {

    /** @var CI_Controller */
    private $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    private function _base_url() {
        return rtrim(site_url('api/business'), '/');
    }

    private function _token() {
        return (string) $this->CI->session->userdata('business_api_jwt');
    }

    /**
     * @return array{ok:bool,status:int,body:array|null,error:string|null}
     */
    public function request($method, $path, $json_body = null) {
        $url = $this->_base_url() . '/' . ltrim($path, '/');
        $ch = curl_init($url);
        $headers = array('Accept: application/json');
        $tok = $this->_token();
        if ($tok !== '') {
            $headers[] = 'Authorization: Bearer ' . $tok;
        }
        $headers[] = 'X-Request-Id: web-' . bin2hex(random_bytes(6));
        // Évite les réponses vides avec Apache + POST (Expect: 100-continue / CURLFile).
        $headers[] = 'Expect:';
        if ($json_body !== null) {
            $headers[] = 'Content-Type: application/json; charset=utf-8';
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_body));
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper((string) $method));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $raw = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            return array('ok' => false, 'status' => $status ?: 0, 'body' => null, 'error' => $err ?: 'Erreur HTTP');
        }
        return $this->_parse_api_response($raw, $status);
    }

    /**
     * Interprète la réponse JSON de Response_format (success / message / data).
     * Ne pas se fier au seul code HTTP 200 : une page HTML ou une notice PHP avant le JSON donnerait un faux positif.
     *
     * @return array{ok:bool,status:int,body:array|null,error:string|null}
     */
    private function _parse_api_response($raw, $status) {
        $raw = is_string($raw) ? $raw : '';
        $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
        $raw = ltrim($raw);
        $body = json_decode($raw, true);
        if (!is_array($body) && $raw !== '') {
            if (preg_match('/\{[\s\S]*"success"\s*:[\s\S]*\}/', $raw, $m)) {
                $body = json_decode($m[0], true);
            }
        }
        $http_ok = ($status >= 200 && $status < 300);
        $is_api = is_array($body) && array_key_exists('success', $body);
        $succ = $is_api ? $body['success'] : null;
        $success_ok = ($succ === true || $succ === 1 || $succ === '1' || $succ === 'true');
        $ok = $http_ok && $is_api && $success_ok;
        $error = null;
        if (!$ok) {
            if ($is_api && isset($body['message'])) {
                $error = (string) $body['message'];
            } elseif (!$http_ok) {
                $error = 'HTTP ' . (string) $status;
            } elseif ($raw === '') {
                $error = 'Réponse HTTP vide (souvent lié à Expect: 100-continue / Apache — réessayez après mise à jour du client, ou vérifiez base_url).';
            } else {
                $json_err = function_exists('json_last_error_msg') ? json_last_error_msg() : '';
                $error = 'Réponse non-JSON ou API invalide' . ($json_err !== '' ? ' (' . $json_err . ')' : '') . '. Vérifiez site_url et que l’URL api/business atteint Apibusiness.';
            }
        }
        if (!$ok && is_string($raw) && strlen($raw) > 0) {
            log_message('error', 'Business_api_client parse fail status=' . $status . ' raw_head=' . substr($raw, 0, 400));
        }
        return array(
            'ok' => $ok,
            'status' => $status,
            'body' => is_array($body) ? $body : null,
            'error' => $error,
        );
    }

    public function get($path) { return $this->request('GET', $path, null); }
    public function post($path, $json_body) { return $this->request('POST', $path, $json_body); }
    public function put($path, $json_body) { return $this->request('PUT', $path, $json_body); }
    public function delete($path) { return $this->request('DELETE', $path, null); }

    /**
     * POST multipart/form-data (uploads).
     *
     * @param array<string,string|int|float|null> $fields
     * @param array<string,array{tmp_name:string,name:string,type?:string}> $files
     * @return array{ok:bool,status:int,body:array|null,error:string|null}
     */
    public function post_multipart($path, array $fields, array $files) {
        $url = $this->_base_url() . '/' . ltrim($path, '/');
        $ch = curl_init($url);
        $headers = array('Accept: application/json');
        $tok = $this->_token();
        if ($tok !== '') {
            $headers[] = 'Authorization: Bearer ' . $tok;
        }
        $headers[] = 'X-Request-Id: web-' . bin2hex(random_bytes(6));
        // Sans ceci, Apache + cURL + multipart peut renvoyer HTTP 200 avec corps vide (Expect: 100-continue).
        $headers[] = 'Expect:';

        $post = array();
        foreach ($fields as $k => $v) {
            if ($v === null) {
                continue;
            }
            $post[$k] = (string) $v;
        }
        foreach ($files as $field => $f) {
            if (empty($f['tmp_name']) || !is_file($f['tmp_name'])) {
                continue;
            }
            $mime = isset($f['type']) && $f['type'] !== '' ? $f['type'] : 'application/octet-stream';
            $post[$field] = new CURLFile($f['tmp_name'], $mime, $f['name']);
        }

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

        $raw = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            return array('ok' => false, 'status' => $status ?: 0, 'body' => null, 'error' => $err ?: 'Erreur HTTP');
        }
        return $this->_parse_api_response($raw, $status);
    }
}

