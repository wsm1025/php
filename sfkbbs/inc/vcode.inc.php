<?php
function vcode($width=120,$height=40,$fontsize=16,$number=4,$dotnumber=120,$line=3){
header('Content-type:image/jpeg');
$img = imagecreatetruecolor($width,$height);//定义图片大小
$colorBg = imagecolorallocate($img,rand(200,255),rand(200,255),rand(200,255));//定义背景颜色
$colorstring = imagecolorallocate($img,rand(10,100),rand(10,100),rand(10,100));//定义字体颜色
$ele = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');//随机字母数组
$code = '';
for($i=0;$i<$number;$i++){
    $code.=$ele[rand(0,count($ele)-1)];
}//for循环 .=创建四位字母组合
imagefill($img,0,0,$colorBg);//填充颜色
for($i=0;$i<$dotnumber;$i++){//随机生成一百个干扰点
    imagesetpixel($img,rand(0,$width-1),rand(0,$height-1),imagecolorallocate($img,rand(100,200),rand(100,200),rand(100,200)));
}
for($i=0;$i<$line;$i++){
    imageline($img,rand(0,60),rand(0,40),rand(60,120),rand(20,40),imagecolorallocate($img,rand(100,200),rand(100,200),rand(100,200)));
}//生成干扰线
// imagestring($img,5,0,0,'abcd',imagecolorallocate($img,rand(10,100),rand(10,100),rand(10,100)));
imagettftext($img,$fontsize,rand(-5,10),rand(5,15),rand(30,35),$colorstring,realpath("../style/Green-Italic-3.ttf"),$code);//字体样式的改变
imagejpeg($img);//输出图片
imagedestroy($img);//释放内存
return $code;
}
?>