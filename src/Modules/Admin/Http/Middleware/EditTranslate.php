<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EditTranslate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if(strpos($response,'{|tran') !== false && strpos($response,'tran|}') !== false){
            $response = $next($request);
            $input_line = $response->getContent();
            $array = preg_split('/\{\|tran([a-z0-9]+)tran\|\}/', $input_line);
            preg_match_all('/\{\|tran([a-z0-9]+)tran\|\}/', $input_line, $output_array);
            $arrayKey = isset($output_array[1]) ? $output_array[1] : [];
            $output = '';
            foreach ($array as $key => $value){
                $value = str_replace('{|entrans|}',"</span>",$value);
                if($this->checkKey($value,$key) && isset($arrayKey[$key])){
                    $output .= $value . '<span style="cursor:pointer" onclick="showTranslateModal(\'' . $arrayKey[$key] . '\'); return false;">&nbsp;💾&nbsp</span>';
                }else{
                    $output .= $value;
                }
            }
            $output = str_replace("{|tran","",$output);
            $output = str_replace("tran|}","",$output);
            $output .= view("admin::components.formedittranslate")->render();
            $response->setContent($output);
            return $response;
        }else{
            return $response;
        }

    }

    public function checkKey($value,$index){
        $value = trim($value);
        if(empty($value) && $index == 0) return true;
        if(mb_substr($value,-7) == '<title>') return false;
        $char = mb_substr($value,-1);
        if($char == ">") return true;
        return  false;
    }
}
