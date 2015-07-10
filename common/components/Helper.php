<?php

namespace common\components;

class Helper {

    /**自动转为超链接*/
    public static function autoLink($text)
    {
        $at = '|@([a-zA-Z0-9]{2,15})\s|';
        $url = '|(href="(.*?)")|';
        $img = '|<img src="(.*?)"|';

        //url替换为超链接
        if(preg_match($url, $text, $urlMatch))
        {
            $prseUrl = parse_url($urlMatch[2]);
            if(isset($prseUrl['host']) && $prseUrl['host'] == \Yii::$app->params['domain']) $text = preg_replace($url, '$1 target="_blank"', $text);
            else $text = preg_replace($url, '$1 target="_blank" rel="nofollow"', $text);
        }

        //将图片加上img-responsive，防止撑破页面
        if(preg_match($img, $text))
        {
            $replace = '<img class="img-responsive" src="$1"';
            $text = preg_replace($img, $replace, $text);
        }

        //将评论中@提到的转为超链接
        if(preg_match($at, $text))
        {
            $replace = '@<a href="/member/$1" target="_blank">$1</a>';
            $text = preg_replace($at, $replace, $text);
        }

        return $text;
    }

    // 回复内容处理，图片链接自动显示图片
    public static function autoLinkReply($text)
    {
        $at = '|@([a-zA-Z0-9]{2,15})\s|';

        //将评论中@提到的转为超链接
        if(preg_match($at, $text))
        {
            $replace = '@<a href="/member/$1" target="_blank">$1</a>';
            $text = preg_replace($at, $replace, $text);
        }

        $text = preg_replace('/(https?:\/\/\S+\.(?:jpg|jpeg|png|gif|bmp|JPG|JPEG|PNG|GIT|BMP))\s+/', '<img class="img-responsive" src="$1"', $text);

        return $text;
    }

    public static function truncateUtf8String($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
        {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
            {
                if ($length < 1.0)
                {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }
            else
            {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen)
        {
            $result .= $etc;
        }
        return $result;
    }

    public static function agent()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;
        if($is_pc){
            return  'is_pc';
        }
        if($is_iphone){
            return  'is_iphone';
        }
        if($is_ipad){
            return  'is_ipad';
        }
        if($is_android){
            return  'is_android';
        }
    }

}