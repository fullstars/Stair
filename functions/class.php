<?php
	class Zdir{
		//文件图标
		function ico($suffix){
			//根据不同后缀显示不同图标
		    switch ( $suffix )
		    {
			    //音频文件
		    	case 'mp3':
		    	case 'wma':
		    	case 'wav':
		    	case 'ape':
		    	case 'flac':
		    		$ico = "fa fa-music";
		    		break;
		    	case 'pdf':
	    			$ico = "fa fa-file-pdf-o";
		    		break;
		    	case 'doc':
		    	case 'docx':
		    		$ico = "fa fa-file-word-o";
		    		break;
		    	case 'xls':
		    	case 'xlsx':
		    		$ico = "fa fa-file-excel-o";
		    		break;
		    	//图片文件
		    	case 'jpg':
		    	case 'png':
		    	case 'gif':
		    	case 'jpeg':
		    	case 'bmp':
		    		$ico = "fa fa-file-image-o";
		    		break;
		    	//压缩包
		    	case 'zip':
		    	case 'rar':
		    	case 'gz':
		    	case '7z':
		    		$ico = "fa fa-file-archive-o";
		    		break;
		    	//windows软件
		    	case 'exe':
		    		$ico = "fa fa-windows";
		    		break;
		    	case 'apk':
		    		$ico = "fa fa-android";
		    		break;
		    	case 'deb':
		    		$ico = "fa fa-linux";
		    		break;
		    	case 'mp4':
		    	case 'm3u8':
		    	case 'flv':
		    	case 'rm':
		    	case 'rmvb':
		    	case 'mkv':
		    	case 'avi':
		    		$ico = "fa fa-file-video-o";
		    		break;
		    	case 'py':
		    	case 'sh':
		    	case 'c':
		    	case 'cpp':
		    	case 'go':
		    		$ico = "fa fa-file-code-o";
		    		break;
		    	default:
		    		$ico = "fa fa-file-text-o";
		    		break;
		    }
		    return $ico;
		}
		//删除某个文件
		function delfile($password,$config,$filepath){

			//对文件进行判断
			$filepath = $this->checkfile($filepath);

			//执行删除文件
			unlink($filepath);
			//返回json数据
			$redata = array(
				"code"		=>	1,
				"msg"		=>	"已删除"
			);
			$redata = json_encode($redata);
			echo $redata;
			exit;
        }
        
	    //验证文件是否是当前目录
	    function checkfile($filepath){
		    //获取当前路径
			$thedir = __DIR__;
			$thedir = str_replace("\\","/",$thedir);
			$thedir = str_replace("/functions","",$thedir);
			#$thedir = str_replace("");

			//如果文件不存在
			if(!is_file($filepath)) {
				$filehash = array(
				"code"	=>	0,
				"msg"	=>	"文件不存在！"
				);
				$filehash = json_encode($filehash);
				echo $filehash;
				exit;
			}
			//如果文件不是项目路径
			if(!strstr($filepath,$thedir)){
				$filehash = array(
				"code"	=>	0,
				"msg"	=>	"目录不正确！"
				);
				
				$filehash = json_encode($filehash);
				echo $filehash;
				exit;
			}
			return $filepath;
	    }
	    //判断是否是mp4
		function video($filepath){
			//echo $filepath;
			//对文件进行判断
			//$filepath = $this->checkfile($filepath);
			//获取文件后缀
			$suffix = explode(".",$filepath);
			$suffix = end($suffix);
			$suffix = strtolower($suffix);

			if(($suffix == 'mp4') || ($suffix == 'm3u8')){
				return true;
			}
			else{
				return false;
			}
		}
		//获取文件后缀
		function suffix($filepath){
			//获取文件后缀
			$suffix = explode(".",$filepath);
			$suffix = end($suffix);
			$suffix = strtolower($suffix);

			return $suffix;
		}
		//如果是指定后缀，显示查看按钮
		function is_text($filepath){
			$suffix = $this->suffix($filepath);
			//设置支持的后缀
			$support = array(
				"txt",
				"py",
				"sh",
				"conf",
				"go",
				"c",
				"cpp"
			);
			$status = false;
			foreach( $support as $value )
			{
				if($suffix == $value){
					$status = true;
					break;
				}
			}
			return $status;
		}
		//文本查看器
		function vtext($filepath){
			//判断文件
			$this->checkfile($filepath);
			//判断文件后缀
			$suffix = $this->suffix($filepath);
			$status = 0;
			
			//设置支持的后缀
			$support = array(
				"txt",
				"py",
				"sh",
				"conf",
				"go",
				"c",
				"cpp"
			);
			//遍历后缀
			foreach( $support as  $value )
			{
				if($suffix == $value){
					$status = 1;
					break;
				}
			}
			if($status != 1){
				echo '不支持的文本格式！';
				exit;
			}
			//打开文件
			$content = file_get_contents($filepath);
			$content = str_replace("<","&lt;",$content);
			$content = str_replace(">","&gt;",$content);

			return $content;
		}
	}
	//预览pdf
	function viewpdf($filepath){
		//对文件进行判断
		$filepath = $this->checkfile($filepath);
		$file = fopen($filepath,"r");
		fclose($file);
		return $file;
	}
	

	$zdir = new Zdir;
?>