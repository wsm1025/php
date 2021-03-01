<?php
if(!defined('IN_WSM')){
    exit('非法请求');
}
function skip($url, $message, $time = 3)
{
	$html = <<<A
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="{$time};URL={$url}" />
    <title>{$message}</title>
    <link href="../sfkbbs/err/css/err.css" rel="stylesheet" type="text/css" />
    <script src="../sfkbbs/err/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
	$(function() {
		var h = $(window).height();
		$('body').height(h);
		$('.mianBox').height(h);
		centerWindow(".tipInfo");
	});

	//2.将盒子方法放入这个方，方便法统一调用
	function centerWindow(a) {
		center(a);
		//自适应窗口
		$(window).bind('scroll resize',
				function() {
					center(a);
				});
	}
	//1.居中方法，传入需要剧中的标签
	function center(a) {
		var wWidth = $(window).width();
		var wHeight = $(window).height();
		var boxWidth = $(a).width();
		var boxHeight = $(a).height();
		var scrollTop = $(window).scrollTop();
		var scrollLeft = $(window).scrollLeft();
		var top = scrollTop + (wHeight - boxHeight) / 2;
		var left = scrollLeft + (wWidth - boxWidth) / 2;
		$(a).css({
			"top": top,
			"left": left
		});
	}
</script>
</head>
<body>
<div class="mianBox">
	<img src="../sfkbbs/err/images/yun0.png" alt="" class="yun yun0" />
	<img src="../sfkbbs/err/images/yun1.png" alt="" class="yun yun1" />
	<img src="../sfkbbs/err/images/yun2.png" alt="" class="yun yun2" />
	<img src="../sfkbbs/err/images/bird.png" alt="" class="bird" />
	<img src="../sfkbbs/err/images/san.png" alt="" class="san" />
	<div class="tipInfo">
		<div class="in">
			<div class="textThis">
				<h2>${message}</h2>
				<p><span>页面自动<a id="href" href="${url}">跳转</a></span><span>等待<b id="wait">${time}</b>秒</span></p>
			</div>
		</div>
	</div>
</div>
</body>
</html>
A;
	echo $html;
	exit;
}
function Isempty($value, $path, $tips,$times=3)
{
	if (empty(trim($value," ")) || empty(str_replace(PHP_EOL, '', $value))) {//防空格处理与enter
		skip($path, $tips,$times);
	}
}
function Isnumber($value, $path, $tips,$times=3)
{
	if (!is_numeric($value)) {
		skip($path, $tips,$times);
	}
}
//处理html标签转义
function DelHtml($data)
{
	if (is_string($data)) {
		return htmlentities($data);
	}
	if (is_array($data)) {
		foreach ($data as $key => $val) {
			$data[$key] = DelHtml($val);
		}
	}
	return $data;
}
//前台是否登陆
function IsLogin($link)
{
	if (isset($_COOKIE['sfk']['name']) && isset($_COOKIE['sfk']['pw'])) {
		$query = "select * from sfk_member where name='{$_COOKIE['sfk']['name']}' and sha1(pw) = '{$_COOKIE['sfk']['pw']}'";
		if (mysqli_num_rows(execute($link, $query)) == 1) {
			$data = mysqli_fetch_assoc(execute($link, $query));
			return $data['id'];
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/*
调用：$page=page(100,10,9);
返回值：array('limit','html')
 参数说明：
$count：总记录数
$page_size：每页显示的记录数
$num_btn：要展示的页码按钮数目
$page：分页的get参数
*/
function page($count, $page_size, $num_btn = 10, $page = 'page')
{
	if (!isset($_GET[$page]) || !is_numeric($_GET[$page]) || $_GET[$page] < 1) {
		$_GET[$page] = 1;
	}
	if ($count == 0) {
		$data = array(
			'limit' => '',
			'html' => ''
		);
		return $data;
	}
	
	//总页数
	$page_num_all = ceil($count / $page_size);
	if ($_GET[$page] > $page_num_all) {
		$_GET[$page] = $page_num_all;
	}
	$start = ($_GET[$page] - 1) * $page_size;
	$limit = "limit {$start},{$page_size}";

	$current_url = $_SERVER['REQUEST_URI']; //获取当前url地址
	$arr_current = parse_url($current_url); //将当前url拆分到数组里面
	$current_path = $arr_current['path']; //将文件路径部分保存起来
	$url = '';
	if (isset($arr_current['query'])) {
		parse_str($arr_current['query'], $arr_query);
		unset($arr_query[$page]);
		if (empty($arr_query)) {
			$url = "{$current_path}?{$page}=";
		} else {
			$other = http_build_query($arr_query);
			$url = "{$current_path}?{$other}&{$page}=";
		}
	} else {
		$url = "{$current_path}?{$page}=";
	}
	$html = array();
	if ($num_btn >= $page_num_all) {
		//把所有的页码按钮全部显示
		for ($i = 1; $i <= $page_num_all; $i++) { //这边的$page_num_all是限制循环次数以控制显示按钮数目的变量,$i是记录页码号
			if ($_GET[$page] == $i) {
				$html[$i] = "<span>{$i}</span>";
			} else {
				$html[$i] = "<a href='{$url}{$i}'>{$i}</a>";
			}
		}
	} else {
		$num_left = floor(($num_btn - 1) / 2);
		$start = $_GET[$page] - $num_left;
		$end = $start + ($num_btn - 1);
		if ($start < 1) {
			$start = 1;
		}
		if ($end > $page_num_all) {
			$start = $page_num_all - ($num_btn - 1);
		}
		for ($i = 0; $i < $num_btn; $i++) {
			if ($_GET[$page] == $start) {
				$html[$start] = "<span>{$start}</span>";
			} else {
				$html[$start] = "<a href='{$url}{$start}'>{$start}</a>";
			}
			$start++;
		}
		//如果按钮数目大于等于3的时候做省略号效果
		if (count($html) >= 3) {
			reset($html);
			$key_first = key($html);
			end($html);
			$key_end = key($html);
			if ($key_first != 1) {
				array_shift($html);
				array_unshift($html, "<a href='{$url}=1'>1...</a>");
			}
			if ($key_end != $page_num_all) {
				array_pop($html);
				array_push($html, "<a href='{$url}{$page_num_all}'>...{$page_num_all}</a>");
			}
		}
	}
	if ($_GET[$page] != 1) {
		$prev = $_GET[$page] - 1;
		array_unshift($html, "<a href='{$url}{$prev}'>« 上一页</a>");
	}
	if ($_GET[$page] != $page_num_all) {
		$next = $_GET[$page] + 1;
		array_push($html, "<a href='{$url}{$next}'>下一页 »</a>");
	}
	$html = implode(' ', $html);
	$data = array(
		'limit' => $limit,
		'html' => $html
	);
	return $data;
}


