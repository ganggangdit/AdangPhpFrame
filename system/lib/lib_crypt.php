<?php
/**
 * @加密解密类
 * @暂时未用到
 */
class Crypt {

        public function encrypt($string, $key) {
                $str_len = strlen($string);
                $key_len = strlen($key);
                for ($i = 0; $i < $str_len; $i++) {
                        for ($j = 0; $j < $key_len; $j++) {
                                $string[$i] = $string[$i] ^ $key[$j];
                        }
                }
                return $string;
        }

        public function decrypt($string, $key) {
                $str_len = strlen($string);
                $key_len = strlen($key);
                for ($i = 0; $i < $str_len; $i++) {
                        for ($j = 0; $j < $key_len; $j++) {
                                $string[$i] = $key[$j] ^ $string[$i];
                        }
                }
                return $string;
        }

}