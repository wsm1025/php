<?php
if(!defined('IN_WSM')){
    exit('非法请求');
}
?>
<style>
.model{
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color:rgba(0,0,0,0.5) ;
	display: none;
}
.photoimg{
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%,-50%);
	background-color: white;
}
</style>
<div class="model">
	<div class="photoimg">

	</div>
</div>
<script>
function show(url){
	document.querySelector('.model').style.display='block';
	var img = document.createElement('img');
	img.src=url;
    img.style.width='100%';
    img.style.height='100%';
	document.querySelector('.photoimg').appendChild(img);
}
document.querySelector('.model').onclick=function(){
	document.querySelector('.model').style.display='none';
	document.querySelector('.photoimg').removeChild(document.querySelector('.photoimg img'));
}
</script>