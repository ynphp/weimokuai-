<?php
 class TextParser{
    public function parseScanQRResponse($fans, $leader, $text){
        $text = preg_replace('/\[nickname\]/', $fans['nickname'], $text);
        $text = preg_replace('/nickname/', $fans['nickname'], $text);
        $text = preg_replace('/fanid/', $fans['fanid'], $text);
        $text = preg_replace('/leader/', $leader['nickname'], $text);
        return htmlspecialchars_decode($text, ENT_QUOTES);
    }
    public function parse($fans, $text){
        $text = preg_replace('/\[nickname\]/', $fans['nickname'], $text);
        $text = preg_replace('/nickname/', $fans['nickname'], $text);
        $text = preg_replace('/fanid/', $fans['fanid'], $text);
        return htmlspecialchars_decode($text, ENT_QUOTES);
    }
    public function batchParse($pattern_value_map, $text){
        foreach ($pattern_value_map as $pat => $value){
            $text = preg_replace($pat, $value, $text);
        }
        return htmlspecialchars_decode($text, ENT_QUOTES);
    }
}
