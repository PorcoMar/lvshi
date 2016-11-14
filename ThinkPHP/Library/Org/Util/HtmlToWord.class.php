<?php
/**
 * Created by IntelliJ IDEA.
 * User: wuxf
 * Date: 16-5-13
 * Time: 下午5:59
 */

namespace Org\Util;


class HtmlToWord
{
    public function start(){
        ob_start();//输出那同输出到缓冲区，而不是到浏览器。
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
        xmlns:w="urn:schemas-microsoft-com:office:word"
        xmlns="http://www.w3.org/TR/REC-html40">';
    }
    public function save($path)
    {
        echo "</html>";
        $data = ob_get_contents();//得到缓冲区的数据
    }
    public function wirtefile($fn,$data)
    {
        $fp = fopen($fn, $data);
        fwrite($fp, $data);
    }
}