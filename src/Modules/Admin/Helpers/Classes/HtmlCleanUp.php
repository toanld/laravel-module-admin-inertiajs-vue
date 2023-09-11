<?php
/**
 * Created by Bùi Văn Chiến (skype: chien88edu).
 * Email: cbquyetchien973@gmail.com - Phone: 0989.197.xxx
 * Date: 2021-07-06
 * Time: 1:58 PM
 */


namespace Modules\Admin\Helpers\Classes;

use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HtmlCleanUp
{
    //Các tag cho phép
    var $valid_elements	= ["a", "b", "blockquote", "br", "center", "del", "div", "em", "font", "h2", "h3", "h4", "h5", "h6", "i", "img", "ins", "li", "hr", "ol",
        "p", "pre", "s", "span", "strong", "sub", "sup", "table", "tbody", "td", "th", "tr", "u", "ul"];

    //Phần mở rộng cho các tag đc phép, các attribute cho phép trong tag
    var $extended_valid_elements = [
        "a"				=> ["href", "title"],
        "b"				=> [],
        "blockquote"	=> [],
        "br"				=> [],
        "center"		=> [],
        "del"			=> [],
        "div"			=> [],
        "em"				=> [],
        "font"			=> [],
        "h2"				=> [],
        "h3"				=> [],
        "h4"				=> [],
        "i"				=> [],
        "img"			=> ["alt", "src"],
        "ins"			=> [],
        "li"				=> [],
        "hr"				=> [],
        "ol"				=> [],
        "p"				=> [],
        "pre"			=> [],
        "s"				=> [],
        "span"			=> [],
        "strong"		=> [],
        "strike"		=> [],
        "sub"			=> [],
        "sup"			=> [],
        "table"			=> [],
        "tbody"			=> [],
        "td"				=> [],
        "th"				=> [],
        "tr"				=> [],
        "u"				=> [],
        "ul"				=> [],
    ];
    //Các style không được phép dùng
    var $invalid_styles = ["behavior", "background-image", "background", "list-style-image", "expression", "/*", "*/"];

    //Các style được phép dùng để override invalid_styles (ví dụ background bị xóa thì vẫn phải cho background-color)
    var $override_styles = ["background" => ["background-color"]];

    //Các giao thức được dùng
    var $web_protocol = ["http://", "https://", "ftp://", "mailto:"];

    var $input_html;
    var $output_html;
    var $DOMDoc;
    //Lưu lại log
    var $log_string = "";

    var $base_url = '';

    var $count_link = 0;
    var $arrayPictures = [];
    var $downloadPicture = false;
    /**
    Khởi tạo hàm
     */
    public function __construct($input_html,$downloadPicture = false){
        //Do something here
        $this->input_html = $input_html;
        if($downloadPicture) $this->downloadPicture = $downloadPicture;
    }

    public function set_base_url($url = ''){
        if($url != ''){
            $array_parse = parse_url($url);
            $host = isset($array_parse['host'])? $array_parse['host'] : '';
            $this->base_url = $array_parse['scheme'] . '://' . $host . '/';
        }
    }

    /**
    Bắt đầu làm sạch chuỗi HTML
     */
    function clean(){
        //Sử dụng strip_tags để làm sạch HTML
        $this->html_strip_tags();

        //$this->output_html = $this->input_html;
        //Sử dụng DOMDocument để làm sạch
        $this->DOMDocument_cleanup();

        //Sau khi đã trải qua công đoạn làm sạch gán outout = input
        $this->output_html = $this->input_html;

        //Cleanup HTML Comment
        $this->output_html = preg_replace('/&lt;!--(.|\s)*?--&gt;/', '&nbsp;', $this->output_html);

        //Convert ký tự NCR -> UTF-8
        $convmap = array(0x0, 0x2FFFF, 0, 0xFFFF);
        $this->output_html = @mb_decode_numericentity($this->output_html, $convmap, "UTF-8");

        $this->output_html = $this->ampReplaceTag($this->output_html);
        $this->dowloadPic();

    }

    function ampReplaceTag($html = ''){
        $$html = preg_replace('#<iframe.*?>(.*?)</iframe>#i', '', $html);
        $html = preg_replace('#xmlns:v="(.*?)"#i', '', $html);
        $html = preg_replace('#xmlns:ib="(.*?)"#i', '', $html);
        $html = preg_replace('#xmlns="(.*?)"#i', '', $html);
        $html = preg_replace('#medium-editor-index="(.*?)"#i', '', $html);
        $html = preg_replace('#spellcheck="(.*?)"#i', '', $html);
        $html = preg_replace('#for="(.*?)"#i', '', $html);

        $html = preg_replace('#<font.*?>(.*?)</font>#i', '<span>\1</span>', $html);
        $html = preg_replace('#<font.*?>(.*?)<font>#i', '<span>\1</span>', $html);
        $html = preg_replace('#<div.*?>#i', '<div>', $html);
        $html = preg_replace('#<p.*?>#i', "<p>", $html);
        $html = preg_replace('#xml:lang="(.*?)"#i', '', $html);
        $html = str_replace('nowrap', '', $html);
        $html = str_replace(['!important', '! important'], '', $html);
        $html = str_replace('<EDIT>', '', $html);
        $html = str_replace('<staff name>', '', $html);
        $html = str_replace('<font', '<span', $html);
        $html = str_replace('</font>', '</span>', $html);
        $html = preg_replace('#(height|border|bordercolor)="(.*)"#i', '', $html);
        $html = preg_replace('/<a.*?href\s*=\s*[\'"](.*?)[\'"].*?>/m', '<a href="$1">', $html);
        $html = preg_replace('/<img(?![^>]*(src="[^"]+"))[^>]*>/m', '', $html);
        $html = str_replace(['"uggc://', '"ttps://', '"htttp://', '"file://', '"hhttps://',
            '"weixin://', '"httsp://', '"htpps://', '"moz-extension://',
            '"htp://', '"chrome://', '"httpss://', '"resource://', '"chrome-extension://',
            '"webkit-fake-url://', '"ihttps://', '"moz-extension://', '"itms://',
            '"market://', '"gethttps://', '"hhttp://', '"fb://', '"instagram://',
            '"emailto://', '"t3://', '"hhttp://', '"applewebdata://', '"ohttps://'], '"http://', $html);
        $html = str_replace('callto://', 'tel:', $html);

        return $html;
    }

    /**
    Sử dụng strip_tags để remove các thẻ ko đc phép
     */
    function html_strip_tags(){

        $tag_allow = "";
        reset($this->valid_elements);
        //Tạo các tag_allow
        foreach ($this->valid_elements as $m_key => $m_value) $tag_allow .= "<" . $m_value . ">";

        //Loại các thẻ ko cho phép
        $this->input_html = strip_tags($this->input_html, $tag_allow);
    }

    /**
    Làm sạch HTML bằng DOMDocument
     */
    function DOMDocument_cleanup(){
        //Khởi tạo 1 DOM Document mới
        $this->DOMDoc = new DOMDocument("1.0", "UTF-8");

        //Cho thẻ HTML, meta UTF8, <body> vào DOM để tránh lỗi khi loadHTML
        $this->input_html = 	'<html>' .
            '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' .
            '<body>' .
            $this->input_html .
            '</body>' .
            '</html>';
        //Load input HTML vào DOM Document, dùng @ để tránh lỗi
        @$this->DOMDoc->loadHTML($this->input_html);

        //Loại bỏ các tag không cho phép
        $this->DOMDocument_cleanup_tag();

        //Loại các thẻ tr, td, th đứng 1 mình ko có cha
        //$this->DOMDocument_cleanup_missing_parent_tr_td();

        //Loại các attribute không được phép
        $this->DOMDocument_cleanup_attribute();

        try{
            //Trả lại input chuỗi HTML đã validate xong
            $this->input_html = $this->DOMDoc->saveHTML();
        }catch (Exception $err){
            $this->input_html = '';
        }

        // Replace các ký tự FCK sang UTF-8
        $this->input_html	= $this->replaceFCK($this->input_html, 1);



        //Tìm đến đầu body và /body để cắt chuỗi
        $start_pos 	= strpos($this->input_html,"<body>");
        $end_pos 	= strpos($this->input_html,"</body>");

        // Không tìm thấy vị trí thẻ body thì trả về chuỗi rỗng
        if($start_pos === false) $this->input_html	= "";
        else $this->input_html = substr($this->input_html, $start_pos + 6, $end_pos - $start_pos - 6);
    }

    function replaceFCK($string, $type=0){
        $array_fck	= array ("&Agrave;", "&Aacute;", "&Acirc;", "&Atilde;", "&Egrave;", "&Eacute;", "&Ecirc;", "&Igrave;", "&Iacute;", "&Icirc;",
            "&Iuml;", "&ETH;", "&Ograve;", "&Oacute;", "&Ocirc;", "&Otilde;", "&Ugrave;", "&Uacute;", "&Yacute;", "&agrave;",
            "&aacute;", "&acirc;", "&atilde;", "&egrave;", "&eacute;", "&ecirc;", "&igrave;", "&iacute;", "&ograve;", "&oacute;",
            "&ocirc;", "&otilde;", "&ugrave;", "&uacute;", "&ucirc;", "&yacute;",
        );
        $array_text	= array ("À", "Á", "Â", "Ã", "È", "É", "Ê", "Ì", "Í", "Î",
            "Ï", "Ð", "Ò", "Ó", "Ô", "Õ", "Ù", "Ú", "Ý", "à",
            "á", "â", "ã", "è", "é", "ê", "ì", "í", "ò", "ó",
            "ô", "õ", "ù", "ú", "û", "ý",
        );
        if($type == 1) $string = str_replace($array_fck, $array_text, $string);
        else $string = str_replace($array_text, $array_fck, $string);
        return $string;
    }

    /**
    Loại bỏ các tag không cho phép
     */
    function DOMDocument_cleanup_tag(){

        $this->log_string .= "---START REMOVE TAG ---\n";

        //Lọc bỏ tag không được phép
        //gắn node với các tất cả các tag dưới dạng tham chiếu
        $node = $this->DOMDoc->getElementsByTagName("*");

        //Khai báo mảng những node cần delete
        $delete_node = [];
        $new_valid_elements = array_merge($this->valid_elements, array("html", "body"));

        foreach ($node as $mynode){

            $this->log_string .= $mynode->nodeName . " ";
            if($this->downloadPicture && strtolower($mynode->nodeName) == "img"){
                $md5 = md5($mynode->getAttribute("src"));
                $this->arrayPictures[$md5] = $mynode->getAttribute("src");
                $mynode->setAttribute("src", $md5);
            }

            if (array_search($mynode->nodeName, $new_valid_elements) === false){
                $this->log_string .= "delete";
                //gán vào delete node
                $delete_node[] = $mynode;
            }
            $this->log_string .= "\n";
        }

        //Loop delete node để xóa
        foreach ($delete_node as $mynode){
            //Tự xóa nó bằng cách nhẩy đến nút cha rồi xóa
            $mynode->parentNode->removeChild($mynode);
        }

    }

    protected function dowloadPic(){
        if($this->downloadPicture){
            foreach ($this->arrayPictures as $md5 => $url){
                if(substr($url, 0,1)== "/" || strpos($url,config('app.url')) !== false){
                    $this->arrayPictures[$md5] = $url;
                }elseif(substr($url, 0,4)== "http"){
                    $this->arrayPictures[$md5] = $this->downloadAndSaveImage($url);
                }else{
                    $this->arrayPictures[$md5] = $url;
                }

            }
            foreach ($this->arrayPictures as $md5 => $url){
                $this->output_html = str_replace($md5,$url,$this->output_html);
            }
        }
    }

    public function downloadAndSaveImage($url)
    {
        $client = new Client();

        // Tải ảnh từ URL
        $response = $client->get($url);

        // Lấy nội dung ảnh
        $imageContent = $response->getBody()->getContents();

        // Tạo tên tệp theo định dạng mong muốn
        $fileTime = time();
        $pathTime = date("Y/m", $fileTime);
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $fileName = $fileTime . '_' . Str::random(5) . '.' . $extension;

        // Lưu ảnh vào thư mục 'public/uploads/' với tên tệp được chỉ định
        Storage::disk('public')->put('uploads/' . $pathTime . "/" . $fileName, $imageContent);

        // Trả về thông báo thành công hoặc làm bất kỳ điều gì bạn cần ở đây
        return "/storage/uploads/" . $pathTime . "/" . $fileName;
    }

    /**
    Loại bỏ các atttribute không được phép dùng
     */
    function DOMDocument_cleanup_attribute(){
        $this->log_string .= "---START REMOVE ATTRIBUTE ---\n";
        //Loop lần 2 để lọc bỏ các Attribute không đc phép
        $node = $this->DOMDoc->getElementsByTagName("*");
        //print_r($node);
        //Loop node
        foreach ($node as $mynode){
            //Nếu nodeName có trong array
            if (isset($this->extended_valid_elements[$mynode->nodeName])){
                $attributes = $mynode->attributes;
                $array_remove = [];
                if($attributes->length > 0){
                    $i = 0;
                    while ($i < $attributes->length) {
                        $attr_name = trim($attributes[$i]->name);
                        $array_remove[$attr_name] = $attributes[$i]->value;
                        $i++;
                    }
                }

                // set rel = nofollow và đếm link trong nội dung
                if($mynode->nodeName  == 'a'){
                    $this->count_link += 1;
                    $mynode->setAttribute('rel', 'nofollow');
                }

                //print_r($array_remove);
                if(!empty($array_remove)){
                    foreach ($array_remove as $key => $value){
                        if (!in_array($key, $this->extended_valid_elements[$mynode->nodeName])){
                            if($key == 'data-src'){
                                $mynode->setAttribute('src', $value);
                            }
                            $mynode->removeAttribute($key);
                        }else{
                            if($key == 'href' || $key == 'src'){
                                if(!filter_var($value, FILTER_VALIDATE_URL)){
                                    $start_with = substr($value,0, 1);
                                    if($start_with == '/'){
                                        $value = substr($value, 1, 600);
                                    }
                                    switch ($key){
                                        case 'href':
                                            $mynode->setAttribute('href', $this->base_url . $value);
                                            break;
                                        case 'src':
                                            $mynode->setAttribute('src', $this->base_url . $value);
                                            break;
                                    }

                                }
                            }
                        }
                    }
                }

            }

        }//End Loop node
    }
    //End DOMDocument_cleanup_attribute method


}
