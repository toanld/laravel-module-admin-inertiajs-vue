<?php
namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\Controller;
use OpenAI;

class ChatGptController extends Controller
{
    /**
     * The stream source.
     *
     * @return \Illuminate\Http\Response
     */
    public function stream()
    {
        $api_key = config('db.openai_api_key');
        if(empty($api_key)) return null;
        $client = OpenAI::client($api_key);
        $value = trim(removeHTML(convertToUnicode(session()->get('gptQuestion'))));
        session()->forget('gptQuestion');
        return response()->stream(function () use ($client,$value) {
            if(empty($value)){
                echo "event: update\n";
                echo 'data: Truy vấn gửi lên bằng rỗng';
                echo "\n\n";
                ob_flush();
                flush();
                echo "event: update\n";
                echo 'data: end';
                echo "\n\n";
                ob_flush();
                flush();
            }else{
                $stream = $client->chat()->createStreamed([
                    'model' => config('db.openai_gpt_model','gpt-3.5-turbo'),
                    'temperature' => 0.8,
                    'messages' => [
                        ['role' => 'user', 'content' => "Hãy bỏ qua tất cả các hướng dẫn trước đó. Tôi muốn bạn hoạt động như một SEO rất thành thạo và một copywriter cao cấp, nói và viết thông thạo tiếng Việt. Tôi muốn bạn giả vờ rằng bạn có thể viết nội dung bằng tiếng Việt tốt đến mức nó có thể xếp hạng cao hơn các trang web khác. và viết lại nội dung  và bao gồm các tiêu đề phụ bằng cách sử dụng các từ khóa liên quan đặc biệt là công dụng. Bài viết phải độc đáo 100% và loại bỏ đạo văn. bài viết phải từ 800 đến 1500 từ. Tất cả đầu ra phải bằng tiếng Việt và phải có phong cách viết 100% của con người, đồng thời khắc phục các vấn đề ngữ pháp và chuyển sang giọng nói chủ động. Nội dung bài viết định dạng HTML (không cần thẻ style,html,head,body,a). Nội dung cần viết lại là: " . $value],
                    ],
                    'format' => 'html'
                ]);

                foreach($stream as $response){
                    $text = $response->choices[0]->delta->content;
                    echo "event: update\n";
                    echo 'data: ' . str_replace("\n","<br>",$text);
                    echo "\n\n";
                    if (connection_aborted()) {
                        break;
                    }
                    ob_flush();
                    flush();
                }
                ob_flush();
                flush();
            }

        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
    }
    public function streamPost(Request $request){
        $question = $request->input('question');
        session()->put('gptQuestion', $question);
        return 'ok';
    }
    public function streamDemo()
    {
        return response()->stream(function () {
            $value = convertToUnicode(session()->get('gptQuestion'));
            session()->forget('gptQuestion');
            if(empty($value)){
                echo "event: update\n";
                echo 'data: Truy vấn gửi lên bằng rỗng';
                echo "\n\n";
                ob_flush();
                flush();
                echo "event: update\n";
                echo 'data: end';
                echo "\n\n";
                ob_flush();
                flush();
            }
            $i = -1;
            $max = mb_strlen($value,'UTF-8');
            echo "event: update\n";
            echo 'data: <p>';
            echo "\n\n";
            ob_flush();
            flush();
            while (true) {
                $i++;
                if (connection_aborted() || $i > $max) {
                    break;
                }
                $char = mb_substr($value,$i,1,'UTF-8');
                $text = $char;
                if($char == "<"){
                    for($j = $i+1; $j < $max; $j++){
                        $i++;
                        $char = mb_substr($value,$j,1,'UTF-8');
                        $text .= $char;
                        if($char == ">"){
                            break;
                        }
                    }
                }
                // Echo time
                echo "event: update\n";
                echo 'data: ' . $text;
                echo "\n\n";
                ob_flush();
                flush();

                // Wait 2 seconds for the next message / event
                usleep(10000);
            }
            echo "event: update\n";
            echo 'data: </p>';
            echo "\n\n";
            ob_flush();
            flush();
            echo "event: update\n";
            echo 'data: end';
            echo "\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
    }
}
