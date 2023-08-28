<?php
/**
 * Created by Nguyễn Mạnh Hùng
 * Email: hungnm1@vatgia.com
 * Date: 10/19/2017
 * Time: 2:16 PM
 */

use RyanNielson\Meta\Meta;

class MyMeta extends Meta
{
    private $linkTags = '';
    private $rowInfo = null;
    private $jsonLD = [];
    private $breadcrum = [];
    private $showAdsense = true;
    private $h1 = null;
    private $modbileDetect = null;
    private $statusCheckIp = null;
    private $logs = [];
    protected $teaser;
    use \Modules\Admin\Helpers\SingletonTrait;

    function setLinkTag($arrAttr = [])
    {
        $attr = ' ';
        foreach ($arrAttr as $key => $val) {
            //nếu là ip viet nam và ko phải googlebot thi ko hiển thị link amp
            if ($val == "amphtml") {
                //nếu là ip việt nam và ko phải google bot thì ko hiển thị json ld
            }
            $attr .= $key . '="' . trim($val) . '" ';
        }
        $this->linkTags .= '<link ' . $attr . ' crossorigin="anonymous" />';
    }

    public function showLinkTags()
    {
        return $this->linkTags;
    }

    function isMobile()
    {
        if (is_null($this->modbileDetect)) {
            $this->modbileDetect = new Mobile_Detect();
        }
        return $this->modbileDetect->isMobile();
    }


    /**
     * @param array $data
     * tạo breadcrum
     */
    public function setBreadcrum($data = [])
    {
        $this->breadcrum[] = $data;
    }

    /**
     * @return array
     * Trả về dữ liệu breadcrum
     */
    public function getBreadcrum()
    {
        return $this->breadcrum;
    }

    public function addLogs($key, $value)
    {
        $this->logs[$key] = $value;
    }

    public function getLogs()
    {
        return json_encode($this->logs);
    }

    /**
     * set jsonLD
     * @param $arr ["@type"=>"content"]
     */
    public function setJsonLD($arr)
    {
        foreach ($arr as $key => $content) {
            $this->jsonLD[$key] = $content;
        }

    }

    public function showJsonBreadcrumb()
    {
        $str = '<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
      ';
        foreach ($this->breadcrum as $k => $breadcrumb) {
            if ($k > 0) {
                $str .= ",";
            }
            $str .= '{
                "@type": "ListItem",
                "position": '.($k+1).',
                "name": "'.$breadcrumb["title"].'",
                "item": "'.$breadcrumb['href'].'"
              }';
        }
        $str .= ']
    }
    </script>';

    return $str;
    }

    public function setJsonLdLocalBusiness($key, $value)
    {
        $this->jsonLD["LocalBusiness"][$key] = $value;
    }

    public function setJsonLdBreadcrumbList($key, $value)
    {
        $this->jsonLD["BreadcrumbList"][$key] = $value;
    }

    public function setJsonLdAggregateRating($key, $value)
    {
        $this->jsonLD["AggregateRating"][$key] = $value;
    }


    public function setH1($text)
    {
        $this->h1 = $text;
    }

    public function h1()
    {
        return $this->h1;
    }

    public function setTeaser($text)
    {
        $this->teaser = $text;
    }

    public function teaser()
    {
        return $this->teaser;
    }

    public function disableAdsense()
    {
        $this->showAdsense = false;
    }

    public function isShowAdsense()
    {
        return $this->showAdsense;
    }

    public function showJsonLD()
    {

        $html = null;
        foreach ($this->jsonLD as $key => $value) {
            $value["@context"] = "https://schema.org";
            $value["@type"] = $key;
            $html .= '<script type="application/ld+json">';
            $html .= json_encode($value, JSON_PRETTY_PRINT);
            $html .= '</script>';
        }
        return $html;
    }

    public function setRowInfo($row)
    {
        $this->rowInfo = $row;
    }



    public function getRowInfo()
    {
        return $this->rowInfo;
    }
}

function meta()
{
    return MyMeta::getInstance();
}
