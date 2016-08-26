<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/4/22
 * Time: 10:13
 */
/**
 * 发送post请求
 * @param string $url      请求地址
 * @param array $post_data post键值对数据
 * @return string
 */
function send_post($url, $post_data)
{
    $postdata = http_build_query($post_data);
    $options  = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context  = stream_context_create($options);
    $result   = file_get_contents($url, false, $context);

    return $result;
}

/**
 * 格式化打印, 并终止
 */
function d($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}

/**
 * 格式化打印, 不终止
 */
function v($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

/**
 * 跳转加提示 -- 错误跳转
 * @param string $text
 * @param string $url
 * @param number $time
 */
function error($text = "", $url = '', $time = 2)
{
    if (empty($text)) $text = '操作有误，请重新操作';
    if (empty($url)) {
        $url = $_SERVER["HTTP_REFERER"];
    }
    echo show_msg($text, true);
    echo '<META HTTP-EQUIV="refresh" CONTENT="' . $time . '; URL=' . $url . '">';
    exit;
}

/**
 * 跳转加提示 -- 成功跳转
 * @param string $text
 * @param string $url
 * @param number $time
 */
function success($text = "", $url = '', $time = 1)
{
    if (empty($text)) $text = '操作成功';
    if (empty($url)) {
        $url = $_SERVER["HTTP_REFERER"];
    }
    echo show_msg($text, true);
    echo '<META HTTP-EQUIV="refresh" CONTENT="' . $time . '; URL=' . $url . '">';
    exit;
}

/**
 * api 返回json
 * @param mixed $data 返回数据(或提示)
 * @param num $status 返回状态
 */
function apireturn($data = '', $status = "0000", $arr = '')
{
    $re = [];

    $re['code'] = $status;

    if ($status == '0000') {

        $re['info'] = $arr;

        $data_res = array();

        if (!empty($data[0])) {
            $re['data'] = $data;
        } else {

            $data_res[] = $data;
            $re['data'] = $data_res;

            if ($data == "[]" || empty($data)) {
                $re['data'] = $data;
            }

        }

        $re['msg'] = 'success';

    } else if ($status == "9999") {
        $re['msg'] = $data;
    }

    echo json_encode($re);

    die;
}

/**
 * api返回的 pagenation 参数打包
 */
function apiPagenation($total = 0, $page = 0, $limit = 0)
{
    $re               = [];
    $re['totalnum']   = $total;
    $re['page']       = $page;
    $re['numPerPage'] = $limit;

    return $re;
}

/**
 * 展示信息到页面
 * @param null $msg    展示的信息
 * @param bool $return 是否作为内容返回
 * @return string
 */
function show_msg($msg = null, $return = false)
{
    $text =
        <<<EOT
<!DOCTYPE html>
<html>
    <head>
        <title>{$msg}</title>
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 4rem;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">{$msg}</div>
            </div>
        </div>
    </body>
</html>
EOT;

    if ($return) return $text;

    echo $text;
    die;
}

/**
 * 临时接口测试表单生成
 * @param array $post  form提交的数组, 如 Request::all()
 * @param array $names 对应的字段, 可以是 ['id', 'name'] 等
 * @return string
 */
function testForm($all = [], $method = 'get', $names = [])
{
    if (empty($names)) $names = ['id'];

    $str = '<form method=' . $method . ' action="?" id="testForm">';
    for ($i = 0; $i < count($names); $i++) {
        // 判断 $name 是否为数组
        if (!empty($names) && !is_array($names)) $names = array($names);

        $name = $names[$i];
        $val  = isset($all[$name]) ? $all[$name] : '';
        $str .= $name . ' : <input type="text" name=' . $name . ' value=' . $val . ' > ';
    }
    echo '<title>' . $val . '</title>';
    $str .= '<input type="submit" value="submit" />';
    $str .= '<input type="reset" onclick="formReset();" value="reset" />';
    $str .= '</form>';
    $str .= '<script>function formReset(id="testForm")
        {
        var inputs = document.getElementById(id).getElementsByTagName("input");
        for (i in inputs) {
            inputs.value = "";
        }
        }</script>';

    return $str;
}

/**
 * 获取随机字符串 (默认随机字母或数字, 如果 $letter 和 $num 都为 true, 则是字母开头)
 * @param int $len    长度
 * @param array $conf ['number', 'letter', 'upper'] 或者 单个的 'num'
 * @return string       期望长度的返回值
 */
function getCode($len = 10, $conf = ['number', 'letter'])
{
    // 源字符串, 去除了数字 1,4,0 ; 去除了字母 i,l,o  易混淆的字符
    $origin_str['number'] = "2356789";
    $origin_str['letter'] = "abcdefghjkmnpqrstuvwxyz";
    $origin_str['upper']  = "ABCDEFGHJKMNPQRSTUVWXYZ";

    // 判断 $conf 类型
    if (!empty($conf) && !is_array($conf)) $conf = array($conf);

    // 拿到指定类型的所有字符串
    $str_all = array_reduce($conf, function ($res, $item) use ($origin_str) {
        return $res . $origin_str[$item];
    });

    // 打乱并截取对应长度的字符串
    $str = substr(str_shuffle($str_all), 0, $len);

    return $str;
}

/**
 * 指针写入文件
 * @param unknown $name 文件名
 * @param unknown $data 内容
 * @param string $mode  打开方式
 */
function file_set($file, $data, $mode = 'a')
{
    if (!$file || !$data) return false;

//    $dir = dirname($file);
//
//    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $fp = fopen($file, $mode);
    fwrite($fp, $data);
    fclose($fp);

    chmod($file, 0777);

    return true;
}

/**
 * 获取文本框的 文本 兼容字符串
 * @param string $str
 * @return string
 */
function getTextareaRealStr($textareaStr = "")
{
    $str = "";
    if ($textareaStr) {
        $strArray = explode("\r\n", $textareaStr);
        foreach ($strArray as $item) {
            $str .= $item . PHP_EOL;
        }
    }

    return $str;
}

/**
 * where 条件处理
 * @param $req
 * @return array
 */
function handleWhere($req)
{
    $where = [];
    if (!empty($req['where'])) {
        foreach ($req['where'] as $k => $v) {
            if (!empty($req['where'][$k])) {
                if ($k == 'id') $where[] = [$k, $v];
                else $where[] = [$k, 'like', '%' . trim($v) . '%'];
            }
        }
    }

    return $where;
}

/**
 * 获取 url 的一级域名, (特殊情况如 .net.cn, .com.cn, .org.cn等, 不可获取)
 * @param $url
 */
function getDomain($url)
{
    preg_match('/[\w][\w-]*\.(?:com\.cn|com|cn|co|net|org|gov|cc|biz|info)(\/|$)/isU', $url, $domain);

    return rtrim($domain[0], '/');
}

/**
 * 是否以某个字符串开头
 * @param  string $word 原生字符串, 如: withName
 * @param  string $str  标识字符串, 如: with
 * @return boolean      返回判断结果
 */
function start_with($word, $str)
{
    if (!empty($word) && !empty($str)) {
        $len = strlen(trim($str));
        if (substr($word, 0, $len) == $str) return true;
    }

    return false;
}
