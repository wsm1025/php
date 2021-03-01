<?php 
if(!defined('IN_WSM')){
    exit('非法请求');
}
function upload($position,$custom_upload_max_length,$name,$type=['jpg','jpeg','gif','png']){
    $return_arr = [];
    $phpini = ini_get('upload_max_filesize');
    $phpini_unit = strtoupper(substr($phpini,-1));//获取配置文件中的值
    $phpini_number = substr($phpini,0,-1);//获取配置文件中的值
    $data_ini = get_multiple_w($phpini_unit);
    $phpini_bytes = $phpini_number*$data_ini;

    $custom_unit = strtoupper(substr($custom_upload_max_length,-1));//获取传进来的值
    $custom_number = substr($custom_upload_max_length,0,-1);//获取传进来的值
    $data_custom = get_multiple_w($custom_unit);
    $custom_bytes = $custom_number*$data_custom;
    if($custom_bytes>=$phpini_bytes){
        $return_arr['err'] = '传入文件过大';
        $return_arr['return'] = false;
        return $return_arr;
    }
    //错误列表
    $arr_erro  = ['','没有错误','上传的文件过大','文件只有部分上传','','找不到临时文件','文件写入失败'];
    //未知错误监测
    if(!isset($_FILES[$name]['error'])){
        $return_arr['err'] = '未知原因失败,请重试';
        $return_arr['return'] = false;;
        return $return_arr;
    }
    //已知错误提示
    if($_FILES[$name]['error']!=0){
        $return_arr['err'] = $arr_erro[$_FILES[$name]['error']];
        $return_arr['return'] = false;
        return $return_arr;
    }
    if($_FILES[$name]['size']>$custom_bytes){
        $return_arr['err'] = '传入文件过大';
        $return_arr['return'] = false;
        return $return_arr;
    }
    //是否为post请求来的数据
    if(!is_uploaded_file($_FILES[$name]['tmp_name'])){
        $return_arr['err'] = '上传文件方式错误';
        $return_arr['return'] = false;
        return $return_arr;
    }
    //匹配文件名
    $file_name =  pathinfo($_FILES[$name]['name']);
    if(!isset($file_name['extension'])){
        $file_name['extension'] ='';
    }
    //检测是否在数组中 in_array()
    if(!in_array($file_name['extension'],$type)){
        $return_arr['err'] = '传入文件后缀名不符合,必须是'.implode(',',$type).'其中一种';
        $return_arr['return'] = false;
        return $return_arr;
    }
    //判断并创建目录
    if(!file_exists($position)){
        if(!mkdir($position,0777,true)){
            $return_arr['err'] = '创建文件失败';
            $return_arr['return'] = false;
            return $return_arr;
        }
    }
    //移动临时文件并重命名
    $new_filename = str_replace('.','',uniqid(mt_rand(1000,9999),true));
    if($file_name['extension']!=''){
        $new_filename .= ".{$file_name['extension']}";
    }
    $position = rtrim($position,'/').'/';
    
    //是否移动成功
    if(!move_uploaded_file($_FILES[$name]['tmp_name'],$position.$new_filename)){
        $return_arr['err'] = '临时文件移动失败';
        $return_arr['return'] = false;
        return $return_arr;
    }
    $return_arr['position'] = $position.$new_filename;
    $return_arr['return'] = true;
    return $return_arr;
}
function get_multiple_w($unit){
    switch($unit){
        case 'K':
            $multiple = 1024;
            break;
        case 'M':
            $multiple = 1024*1024;
            break;
        case 'G':
            $multiple = 1024*1024*1024;
            break;
    }
    return $multiple;
}
?>