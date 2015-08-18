<?php  

/**
*
* Zip Site Installer 1.0.0
* Copyright, Richard Mcfriend (Ore Richard Muyiwa)
* 2015
*
*
* GNU General Public License.
*
*
* ZeusCart V4 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 4 of the License, or
* (at your option) any later version.
* 
* ZeusCart V4 is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with Foobar. If not, see <http://www.gnu.org/licenses/>.
*
*/

error_reporting(0);

ini_set("max_execution_time", 300);

$error = null;
$success = null;

function install($file = null, $target = null, $access = 0){
	
	global $error, $success;
	
	if($file == null)$file = "site.zip";
	if($target == null)$target = __DIR__;
	
	if(!file_exists($file) || !(strtolower(substr($file, strlen($file) - 4, strlen($file) - 1))  == ".zip")) $error = "Sorry! An installable file cannot be found";
	
	if($target != null){
		
		if($access == 0) $sec = 0777;
		else if($access == 1) $sec = 0666;
		else if($access > 1 || $access < 0){
			$error = "Invalid access specified.";
		}
		
		if(!is_dir($target)){
			if($error == null) @mkdir($target, $sec);
		} else {
			if($error == null) @chmod($target, $sec);
		}
	}
	
	if($error == null){
		
		if($zip = new ZipArchive()){
			if(!$zip->open($file)){
				
				$error = "Sorry! The zip file either does not exist or is damaged.";
			} else {
				if(!$zip->extractTo($target)){
					
					$error = "Sorry! Cannot extract to the target directory. Please make sure that you have permission to create files in the target directory.";
				} else {
					
					$success = "Congrats! Your site have been successfully installed.";
				}
			}
		} else {
			
			$error = "Sorry! Zip Site Installer is yet to support your version of PHP";
		}
	}
}

if(isset($_REQUEST["install"])){
	
	if(!empty($_REQUEST["file"])) $file = $_REQUEST["file"]; else $file = null;
	if(!empty($_REQUEST["target"])) $target = $_REQUEST["target"]; else $target = null;
	if(!empty($_REQUEST["access"])) $access = $_REQUEST["access"]; else $access = 0;
	
	if(install($file, $target, $access)){
		
	} else {
		
	}
}

if($error != null) $error = "<div class='error'><small>".$error."</small></div>";
if($success != null) $success = "<div class='success'><small>".$success."</small></div>";

?>
<style>
body{
	font-weight: 100;
	font-family: sans-serif;
	font-size: 14px;
}
form, aside{
	width: 300px;
	margin: 0 auto;
	padding: 20px;
	border: 1px solid #e3e3e3;
	border-radius: 5px;
	margin-top: 1%;
}
aside{
	margin-top: 5px;
	font-size: 0.85em;
}
label{
	display: block;
	margin-bottom: 10px;
}
legend{
	display: block;
	margin: 10px 0;
	border-bottom: 1px solid #e3e3e3;
	font-size: 22px;
	width: 100%;
}
input[type="text"], select{
	padding: 10px;
	border: 1px solid #e3e3e3;
	border-radius: 3px;
	width: 100%;
	margin-bottom: 10px;
}
input[type="submit"]{
	padding: 10px 24px;
	border-radius: 3px;
	border: 1px solid #e3e3e3;
	background: #f1f1f1;
}
input[type="submit"]:hover{
	background: #e8e8e8;
	cursor: pointer;
}
.pulldown{
	margin-top: 20px;
	text-align: right;
	font-size: 0.85em;
	color: #999;
}
ol, li{
	margin: 0;
	padding: 0;
}
ol{
	margin-left: 20px;
}
small{
	color: #999;
	margin-top: 5px;
	display: block;
}
#advanced{
	display: none;
	padding: 20px;
	border: 1px solid #e3e3e3;
	margin-bottom: 10px;
	border-radius: 3px;
	border-top: 0;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
}
a{
	text-decoration: none;
	margin-bottom: 5px;
	display: block;
}
.error, .success{
	background: #fddfdf;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid rgba(0,0,0,.05);
	border-radius: 3px;
}
.success{
	background: #dffddf;
}
.error small{
	color: #a00;
}
.success small{
	color: #0a0;
}
</style>
<form action="#" method="post">
	<legend>Zip Site Installer</legend>
	<?php echo $error.$success; ?>
	<label>Enter the name of the zip file you want to install here.<small>Leave blank if you have named your file as site.zip</small></label>
	<input type="text" name="file" />
	<a id="advancedToggle" href="#">+ Show advanced options</a>
	<div id="advanced">
		<label>Enter path where you want to install it.<small>Leave blank if you are installing in the same directory</small></label>
		<input type="text" name="target" />
		<label>Site files access</label>
		<select name="access">
			<option value="0">Read/Write</option>
			<option value="1">Read Only</option>
		</select>
	</div>
	<div style="text-align:right">
		<input type="submit" name="install" value="Install" />
	</div>
</form>
<aside>
	<label><strong>How to use:</strong></label>
	<ol>
	<li>Zip up the files of your site in a single zip file</li><li>Upload the zip file to your hosting server</li> <li>Upload Zip Site Installer to your hosting server and navigate your browser to Zip Site Installer</li> <li>Enter the name of your sites zip file into the space provided above and click Install.</li>
	</ol>
</aside>
<aside style="padding:8px 20px">
	<label style="margin:0;text-align:right;color:#999">&copy; <?php echo date("Y"); ?> Richard Mcfriend</label>
</aside>

<script>
var ad = document.getElementById("advanced"), adt = document.getElementById("advancedToggle");
adt.onclick = function(e){
	e.preventDefault();
	if(ad.style.display == "block"){
		ad.style.display = "none";
		adt.innerHTML = "+ Show advanced options";
	} else {
		ad.style.display = "block";
		adt.innerHTML = "- Show advanced options";
	}
}
</script>
