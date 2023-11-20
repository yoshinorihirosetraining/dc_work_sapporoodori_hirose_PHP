<?php

/**
* 配列の各キーにpreg_matchを実施し、最初にマッチした検索結果を返す
* 
* @param string $pattern 検索するパターン
* @param array $array 入力する配列
* @return array 検索結果もしくはnull
*/
function match_keyword_from_array($pattern, $array) {
    foreach ($array as $key => $val) {
        $result = preg_match($pattern, $key, $match, PREG_OFFSET_CAPTURE);
        if ($result) {
            return $match;
        }
    }
    return null;
}
