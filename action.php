
<?php
include("config.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
function format_folder_size($size)
{
 if ($size >= 1073741824)
 {
  $size = number_format($size / 1073741824, 2) . ' GB';
 }
    elseif ($size >= 1048576)
    {
        $size = number_format($size / 1048576, 2) . ' MB';
    }
    elseif ($size >= 1024)
    {
        $size = number_format($size / 1024, 2) . ' KB';
    }
    elseif ($size > 1)
    {
        $size = $size . ' bytes';
    }
    elseif ($size == 1)
    {
        $size = $size . ' byte';
    }
    else
    {
        $size = '0 bytes';
    }
 return $size;
}

function get_folder_size($folder_name)
{
 $total_size = 0;
 $file_data = scandir($folder_name);
 foreach($file_data as $file)
 {
  if($file === '.' or $file === '..')
  {
   continue;
  }
  else
  {
   $path = $folder_name . '/' . $file;
   $total_size = $total_size + filesize($path);
  }
 }
 return format_folder_size($total_size);
}

if(isset($_POST["action"]))
{
 if($_POST["action"] == "fetch")
 {
  $folder = array_filter(glob('*'), 'is_dir');
  
  usort($folder, function($x, $y) {
    return filemtime($x) < filemtime($y);
 });
  $output = '<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search Folder.." title="Type in a name">
  <table id="myTable" class="table table-bordered table-hover">
   <tr>
    <th>Folder Name</th>
    <th>Total File</th>
    <th>Size</th>
    <th>Date Modified</th>
    <th>Date Expiry</th>
    <th>Update</th>
    <th>Delete</th>
    <th>Upload File</th>
    <th>View Uploaded File</th>
   </tr>
   ';
  if(count($folder) > 0)
  {
   foreach($folder as $name)
   {
    $Select_Folder = mysqli_query($con,"SELECT * FROM docfolder WHERE doc_name = '$name'");
    $num_rows = mysqli_num_rows($Select_Folder);
    $ResultFolder = "";
    $time = date("Y-m-d");
    $colorTR = "";
    $disabledTr = "";
    if($num_rows>0){
        $Result_Folder = mysqli_fetch_array($Select_Folder);
        $ResultFolder = $Result_Folder['doc_date'];
        if($time >= $Result_Folder['doc_date']){ 
            $colorTR = "class='bg-danger'"; 
            $disabledTr = "disabled";
        };
    }else{
        $ResultFolder = "";
    }
    
    //if($time == $Result_Folder['doc_date']){ echo "test"; };
    
   
   
    
    
    $output .= '
     <tr '.$colorTR.'>
      <td>'.$name.'</td>
      <td>'.(count(scandir($name)) - 2).'</td>
      <td>'.get_folder_size($name).'</td>
      <td>'.@date('F d, Y, H:i:s', filemtime($name)).'</td>
      <td>'.$ResultFolder.'</td>
      <td><button type="button" name="update" data-name="'.$name.'" class="update btn btn-warning btn-xs" '.$disabledTr.'>Update</button></td>
      <td><button type="button" name="delete" data-name="'.$name.'" class="delete btn btn-danger btn-xs">Delete</button></td>
      <td><button type="button" name="upload" data-name="'.$name.'" class="upload btn btn-info btn-xs" '.$disabledTr.'>Upload File</button></td>
      <td><button type="button" name="view_files" data-name="'.$name.'" class="view_files btn btn-default btn-xs">View Files</button></td>
     </tr>';
   }
  }
  else
  {
   $output .= '
    <tr>
     <td colspan="6">No Folder Found</td>
    </tr>
   ';
  }
  $output .= '</table>';
  echo $output;
 }
 
 if($_POST["action"] == "create")
 {
  if(!file_exists($_POST["folder_name"])) 
  {
   mkdir($_POST["folder_name"], 0777, true);
   $folderName = $_POST["folder_name"];
   $folderDate = $_POST["folder_date"];
   $Create_Folder = mysqli_query($con,"INSERT INTO docfolder(doc_name, doc_date) VALUES ('$folderName','$folderDate')");
   echo 'Folder Created';
  }
  else
  {
   echo 'Folder Already Created';
  }
 }
 if($_POST["action"] == "change")
 {
  if(!file_exists($_POST["folder_name"]))
  {
   $renameOldName = $_POST["old_name"];
   $renameNewName = $_POST['folder_name'];
   $Rename_Folder = mysqli_query($con,"UPDATE docfolder SET doc_name='$renameNewName' WHERE doc_name='$renameOldName'");
   rename($_POST["old_name"], $_POST["folder_name"]);
   
   echo 'Folder Name Change';
  }
  else
  {
   echo 'Folder Already Created';
  }
 }
 
 if($_POST["action"] == "delete")
 {
  $files = scandir($_POST["folder_name"]);
  $delFiles = $_POST["folder_name"];
  foreach($files as $file)
  {
   if($file === '.' or $file === '..')
   {
    continue;
   }
   else
   {
    unlink($_POST["folder_name"] . '/' . $file);
   }
  }
  if(rmdir($_POST["folder_name"]))
  {
    $query_delFolder = mysqli_query($con,"DELETE FROM docfolder WHERE doc_name = '$delFiles'");
   echo 'Folder Deleted';
  }
 }
 
 if($_POST["action"] == "fetch_files")
 {
  $file_data = scandir($_POST["folder_name"]);
  $output = '<form action="merge.php">
  <input type="hidden" name="folderName" value="'.$_POST['folder_name'].'"/>
  <button type="submit" >Merge All File</button>
  </form>
  <table class="table table-bordered table-striped">
   <tr>
    <th>Date Upload</th>
    <th>File Name</th>
    <th>Download</th>
    <th>Delete</th>
   </tr>
  ';
  
  foreach($file_data as $file)
  {
   if($file === '.' or $file === '..')
   {
    continue;
   }
   else
   {
    $path = $_POST["folder_name"] . '/' . $file;
    $output .= '
    <tr>
    <!--<td contenteditable="true" data-folder_name="'.$_POST["folder_name"].'"  data-file_name = "'.$file.'" class="change_file_name">'.$file.'</td> -->
    
     <td>'.date ("h:i:sa d/m/y", filemtime($path)).'</td>
     <td data-folder_name="'.$_POST["folder_name"].'"  data-file_name = "'.$file.'" class="change_file_name">'.$file.'</td>
     <td><a class="btn btn-primary btn-xs" href="'.$path.'" target="_blank">Download</a></td>
     <td><button name="remove_file" class="remove_file btn btn-danger btn-xs" id="'.$path.'">Remove</button></td>
    </tr>
    ';
   }
  }
  $output .='</table>';
  echo $output;
 }
 
 if($_POST["action"] == "remove_file")
 {
  if(file_exists($_POST["path"]))
  {
   unlink($_POST["path"]);
   echo 'File Deleted';
  }
 }
 
 if($_POST["action"] == "change_file_name")
 {
  $old_name = $_POST["folder_name"] . '/' . $_POST["old_file_name"];
  $new_name = $_POST["folder_name"] . '/' . $_POST["new_file_name"];
  if(rename($old_name, $new_name))
  {
   echo 'File name change successfully';
  }
  else
  {
   echo 'There is an error';
  }
 }
}
?>