<?php
	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
	//载入配置文件
	include_once("./config.php");
	//载入类
	include_once("./functions/class1.php");
	@$admin = $_GET['admin'];
	//获取当前目录
	$thedir = __DIR__;
	$i = 0;
	

	//获取目录
	$dir = $_GET['dir'];
	//对目录进行过滤
	if((stripos($dir,'./')) || (stripos($dir,'.\\'))){
		echo '非法请求！';
		exit;
	}
	//分割字符串
	$navigation = explode("/",$dir);

	if(($dir == '') || (!isset($dir))) {
		$listdir = scandir($thedir);
	}
	else{
		$listdir = scandir($thedir."/".$dir);
	}

	//计算上级目录
	function updir($dir){
		//分割目录
		$dirarr = explode("/",$dir);
		$dirnum = count($dirarr);
		
		#var_dump($dirarr);
		if($dirnum == 2) {
			$updir = 'index.php';
		}
		else{
			$updir = '';
			for ( $i=1; $i < ($dirnum - 1); $i++ )
			{ 
				$next = $i + 1;
				$updir = $updir.'/'.$dirarr[$i];
				
			}
			$updir = 'index.php?dir='.$updir;
		}
		return $updir;
	}
	#echo updir($dir);
	$updir = updir($dir);
?>
<?php
	//载入页头
	include_once("./page/start.php")
?>
	<!--面包屑导航-->
	<div id="navigation">
		<div class="">
			<p>
				当前位置：<a href="./">首页</a> 
				<!--遍历导航-->
				<?php foreach( $navigation as $menu )
				{
					$remenu = $remenu.'/'.$menu;
					
					if($remenu == '/'){
					$remenu = $menu;
					}	
				?>
				<a href="./index.php?dir=<?php echo $remenu; ?>"><?php echo $menu; ?></a> / 
				<?php } ?>
			</p>
		</div>	
	</div>

	<!--遍历目录-->
	<div id="list" class="mdui-table-fluid">
        <table class="mdui-table">
          <thead class="mdui-color-grey-200">
            <tr>
              <th>文件名</th>
              <th>修改时间</th>
              <th>文件大小</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <!-- <tr>
              <td>tsst</td>
			  <td>tsst</td>
			  <td>tsst</td>
			  <td>tsst</td>
            </tr>
            <tr>
			<td>tsst</td>
			<td>tsst</td>
			<td>tsst</td>
			<td>tsst</td>
			</tr> -->
				<?php foreach( $listdir as $showdir ) {
					//防止中文乱码
					//$showdir = iconv('gb2312' , 'utf-8' , $showdir );
					$fullpath = $thedir.'/'.$dir.'/'.$showdir;
					$fullpath = str_replace("\\","\/",$fullpath);
					$fullpath = str_replace("//","/",$fullpath);
						    
					//获取文件修改时间
					$ctime = filemtime($fullpath);
					$ctime = date("Y-m-d H:i",$ctime);

						    
					//搜索忽略的目录
					if(array_search($showdir,$ignore)) {
							continue;
						}
						    
				 	//判读文件是否是目录,当前路径 + 获取到的路径 + 遍历后的目录
					if(is_dir($thedir.'/'.$dir.'/'.$showdir)){
						$suffix = '';
						//设置上级目录
						if($showdir == '..'){
						$url = $updir;
							}
						else{
							$url = "./index.php?dir=".$dir.'/'.$showdir;
							}
							    
						$ico = "fa fa-folder-open";
						$fsize = '-';
						//返回类型
						 $type = 'dir';
						}
					//如果是文件
					if(is_file($fullpath)){
					//获取文件后缀
					$suffix = explode(".",$showdir);
					$suffix = end($suffix);
						    	
					$url = '.'.$dir.'/'.$showdir;

					//获取文件大小
					$fsize = filesize($fullpath);
					$fsize = ceil ($fsize / 1024);
					if($fsize >= 1024) {
						$fsize = $fsize / 1024;
						$fsize = round($fsize,2).'MB';
						}
					else{
						$fsize = $fsize.'KB';
						}
					$type = 'file';
					#$info = "<a href = ''><i class='fa fa-info-circle' aria-hidden='true'></i></a>";
					}
					//其它情况，可能是中文目录
					else{
						$suffix = '';
						//设置上级目录
						if($showdir == '..'){
							$url = $updir;
							}
						else{
							$url = "./index.php?dir=".$dir.'/'.$showdir;
							}
							    
						$ico = "fa fa-folder-open";
						$fsize = '-';
						$type = 'dir';
						}
					$i++;
				?>
					    <tr id = "id<?php echo $i; ?>">
						    <td>
							    <!--判断文件是否是图片-->
							    <?php if(($suffix == 'jpg') || ($suffix == 'jpeg') || ($suffix == 'png') || ($suffix == 'gif') || ($suffix == 'bmp')){

							   	?>
							   	<a href="<?php echo $url ?>" id = "url<?php echo $i; ?>" onmouseover = "showimg(<?php echo $i; ?>,'<?php echo $url; ?>')" onmouseout = "hideimg(<?php echo $i; ?>)"><i class="<?php echo $ico; ?>"></i> <?php echo $showdir; ?></a>
							   	<div class = "showimg" id = "show<?php echo $i; ?>"><img src="" id = "imgid<?php echo $i; ?>"></div>
							   	<?php }else{ ?>
							    <a href="<?php echo $url ?>" id = "url<?php echo $i; ?>"><i class="<?php echo $ico; ?>"></i> <?php echo $showdir; ?></a>
							    <?php } ?>
						    </td>
					    </tr>
			<?php } ?>			
          </tbody>
        </table>
    </div>
<?php
	//载入页脚
	include_once("./page/end.php")
?>