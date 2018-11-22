<?php
/**
 * Created by PhpStorm.
 * User: childeYin<尹少爷>
 * Date: 17/07/2018
 * Time: 4:34 PM
 */

class ZalyRsa
{
    private $logger;
    private $option = OPENSSL_PKCS1_PADDING;

    public function __construct()
    {
        $this->logger = new Wpf_Logger();
    }

    public function encrypt($data, $key)
    {
        openssl_public_encrypt($data, $crypted, $key, $this->option);
        return $crypted;
    }


    public function decrypt($data, $key)
    {
        openssl_public_decrypt($data, $decrypted, $key, $this->option);
        return $decrypted;
    }


    public function sign($data, $privateKey)
    {
        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        return $signature;
    }

    public function sign_verify($data, $signature, $publicKey)
    {
        if (substr($publicKey, 0, 30) == "-----BEGIN RSA PUBLIC KEY-----") {
            return $this->PKCS_OpensslVerify($data, $signature, $publicKey);
        }
        return openssl_verify($data, $signature, $publicKey, OPENSSL_ALGO_SHA256);
    }

    /**
     * Openssl支持的是X509格式的RSA格式
     * 当前IOS生成的是PKCS格式的RSA
     * 此方法将PKCS格式换转成X509
     *
     * @param $data
     * @param $signature
     * @param $publicKey
     * @return bool 兼容老版本 return true
     */
    private function PKCS_OpensslVerify($data, $signature, $publicKey)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        try {
            $newPublicKey = $this->turnRSAPSCK1ToX509($publicKey);
            $result = openssl_verify($data, $signature, $newPublicKey, OPENSSL_ALGO_SHA256);
        } catch (Exception $e) {
            $this->logger->error($tag, $e);
        }
        return true;
    }

    /**
     * -----BEGIN PUBLIC KEY----- : x.509 pem public key
     * -----BEGIN RSA PUBLIC KEY----- : PKCS#1 RSAPublicKey
     *
     * @param $oldPubkPem
     * @return string
     */
    private function turnRSAPSCK1ToX509($oldPubkPem)
    {
        $key = str_replace([
            '-----BEGIN RSA PUBLIC KEY-----',
            '-----END RSA PUBLIC KEY-----',
            "\r\n",
            "\n",
        ], [
            '',
            '',
            "\n",
            ''
        ], $oldPubkPem);

        $key = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A' . trim($key);

        $key = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
        return $key;
    }

    function rsa_encrypt($plain_text, $public_key)
    {
        openssl_public_encrypt(str_pad($plain_text, 128, "\0", STR_PAD_LEFT), $encrypted, $public_key, OPENSSL_NO_PADDING);
        return bin2hex($encrypted);
    }

    function rsa_decrypt($encrypted, $private_key)
    {
        openssl_private_decrypt(hex2bin($encrypted), $plain_text, $private_key, OPENSSL_NO_PADDING);
        return ltrim($plain_text, "\0");
    }

}
