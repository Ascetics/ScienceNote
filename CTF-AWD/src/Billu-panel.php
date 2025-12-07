<?php
session_start();

include('c.php');
include('head2.php');
if(@$_SESSION['logged']!=true )
{
		header('Location: index.php', true, 302);
		exit();
	
}



echo "Welcome to billu b0x ";
echo '<form method=post style="margin: 10px 0px 10px 95%;"><input type=submit name=lg value=Logout></form>';
if(isset($_POST['lg']))
{
	unset($_SESSION['logged']);
	unset($_SESSION['admin']);
	header('Location: index.php', true, 302);
}
echo '<hr><br>';

echo '<form method=post>

<select name=load>
    <option value="show">Show Users</option>
	<option value="add">Add User</option>
</select> 

 &nbsp<input type=submit name=continue value="continue"></form><br><br>';
if(isset($_POST['continue']))
{
	$dir=getcwd();
	$choice=str_replace('./','',$_POST['load']);
	
	if($choice==='add')
	{
       		include($dir.'/'.$choice.'.php');
			die();
	}
	
    if($choice==='show')
	{
        
		include($dir.'/'.$choice.'.php');
		die();
	}
	else
	{
		include($dir.'/'.$_POST['load']);
	}
	
}


if(isset($_POST['upload']))
{
	
	$name=mysqli_real_escape_string($conn,$_POST['name']);
	$address=mysqli_real_escape_string($conn,$_POST['address']);
	$id=mysqli_real_escape_string($conn,$_POST['id']);
	
	if(!empty($_FILES['image']['name']))
	{
		$iname=mysqli_real_escape_string($conn,$_FILES['image']['name']);
		$r=pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION); // 提取了文件扩展名
		$image=array('jpeg','jpg','gif','png'); // 文件扩展名白名单
		if(in_array($r,$image))
		{
			$finfo = @new finfo(FILEINFO_MIME); // 创建一个finfo对象，用于文件信息检测。
												// @ 符号用于抑制可能出现的错误。
												// FILEINFO_MIME 常量表示我们要获取MIME类型
			$filetype = @$finfo->file($_FILES['image']['tmp_name']);
												// 获取上传文件的MIME类型
												// $_FILES['image']['tmp_name'] 是上传文件的临时存储路径		
												// 底层调用关系
												// PHP finfo类 → libmagic库 → 系统的magic.mgc数据库
												// JPEG:     FF D8 FF E0           // JFIF格式
												// PNG:      89 50 4E 47 0D 0A 1A 0A
												// GIF:      47 49 46 38 37 61 或 47 49 46 38 39 61										
			if(preg_match('/image\/jpeg/',$filetype )  || preg_match('/image\/png/',$filetype ) || preg_match('/image\/gif/',$filetype ))
			{
				if (move_uploaded_file($_FILES['image']['tmp_name'], 'uploaded_images/'.$_FILES['image']['name']))
							{
							echo "Uploaded successfully ";
							$update='insert into users(name,address,image,id) values(\''.$name.'\',\''.$address.'\',\''.$iname.'\', \''.$id.'\')'; 
							mysqli_query($conn, $update);
							
						}
			}
			else
			{
				echo "<br>i told you dear, only png,jpg and gif file are allowed";
			}
		}
	else
	{
		echo "<br>only png,jpg and gif file are allowed";
		
	}
}


}

?>
