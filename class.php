<?php
	class Editor {
	  
    var $mysql_host = 'localhost';
    var $mysql_user = 'etag';
    var $mysql_pass = '4S3p3Q6y';
    var $mysql_db = 'r2';
	  
    public function progress($filename) {
      
      $file = basename($filename, '.mp4');
      
      $full = filesize('./upload/' . $file . '.mp4') * 0.9;
      $progress = filesize('./upload/' . $file . '-output.mp4');
      
      echo ceil($progress / $full * 100);
      
    }
    
	  public function renderComplete($id) {
	    
	    $db = mysql_connect($this->mysql_host, $this->mysql_user, $this->mysql_pass);
      mysql_select_db($this->mysql_db, $db);
	    
	    mysql_query("UPDATE video SET rendered = 1 WHERE id = '$id' LIMIT 1;");
      
	  }
    
    public function getPath($id) {
      
      $db = mysql_connect($this->mysql_host, $this->mysql_user, $this->mysql_pass);
      mysql_select_db($this->mysql_db, $db);
      
      $result = mysql_fetch_assoc(mysql_query("SELECT name FROM video WHERE id='$id';"));
      
      return './upload/' . $result['name'];
      
    }
    
    public function get($id,$public=false) {
      
      $db = mysql_connect($this->mysql_host, $this->mysql_user, $this->mysql_pass);
      mysql_select_db($this->mysql_db, $db);
      
      $result = mysql_fetch_assoc(mysql_query("SELECT labels FROM video WHERE id='$id';"));
      
      if($public) {
        return $result['labels'];
      } else {
        print_r($result['labels']);
      }
    }

    public function getList() {
      $db = mysql_connect($this->mysql_host, $this->mysql_user, $this->mysql_pass);
      mysql_select_db($this->mysql_db, $db);

      $result = mysql_query("SELECT * FROM video");

      $data = array();
      while($row = mysql_fetch_assoc($result)) {
        $data[] = $row;
      }

      return $data;
    }

    public function getTitle($id) {
      
      $db = mysql_connect($this->mysql_host, $this->mysql_user, $this->mysql_pass);
      mysql_select_db($this->mysql_db, $db);
      
      $result = mysql_fetch_assoc(mysql_query("SELECT title FROM video WHERE id='$id';"));
      
      return $result['title'];
      
    }

    public function editTitle($title, $id) {
      $db = mysql_connect($this->mysql_host, $this->mysql_user, $this->mysql_pass);
      mysql_select_db($this->mysql_db, $db);
      
      $result = mysql_query("UPDATE video SET title = '" . $title ."' WHERE id='$id' LIMIT 1;");

      echo $title;
    }
    
    public function save($file, $data, $id) {
      
      function removeDirectory($dir) {
        if ($objs = glob($dir."/*")) {
           foreach($objs as $obj) {
             is_dir($obj) ? removeDirectory($obj) : unlink($obj);
           }
        }
        rmdir($dir);
      }
      
      $db = mysql_connect($this->mysql_host, $this->mysql_user, $this->mysql_pass);
      mysql_select_db($this->mysql_db, $db);
      
      $imgs = array();
      $l = sizeof($data);

      for($i = 0; $i < $l; $i++) {
        
        if(strlen($data[$i]['data']) > 128) {
          if(strpos($data[$i]['data'], "data:image/svg+xml;")!==false) {
            $data[$i]['svg'] = $data[$i]['data'];
          } else {
            $imgs[$i] = imagecreatefromstring(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data[$i]['data'])));
          }
        } else {
          $imgs[$i] = imagecreatefrompng($data[$i]['data']);
        }
        unset($data[$i]['data']);
        
      }

      if($id > 0) mysql_query("UPDATE video SET labels='" . json_encode($data,JSON_UNESCAPED_UNICODE) . "' WHERE id = '$id';");
      else {
        mysql_query("INSERT INTO video (name, labels, date) VALUES ('" . basename($file) . "', '" . json_encode($data,JSON_UNESCAPED_UNICODE) . "', '" . date("Y-m-d") . "');");

        $id = mysql_insert_id();
        
      }
      
      removeDirectory(realpath('./upload/') . '/' . $id . '/');
      
      $file_pref = basename($file, '.mp4');
      
      mysql_close($db);

      mkdir(realpath('./upload/') . '/' . $id . '/');
      
      for($i = 0; $i < $l; $i++) {
        if(!empty($data[$i]['svg'])) continue;
        
        $filename_original = "./upload/$id/$i-original.png";
        $filename = "./upload/$id/$i.png";
        imagealphablending($imgs[$i], true);
        imagesavealpha($imgs[$i], true);
        imagepng($imgs[$i], $filename_original);
        
        list($width, $height) = getimagesize($filename_original);
        
        $work = imagecreatetruecolor($data[$i]['width'], $data[$i]['height']);
        imagealphablending($work, true);
        imagesavealpha($work, true);
        imagefill($work, 0, 0, imagecolorallocatealpha($work, 0, 0, 0, 127));
        $original = imagecreatefrompng($filename_original);
        
        imagecopyresampled($work, $original, 0, 0, 0, 0, $data[$i]['width'], $data[$i]['height'], $width, $height);
        
        imagepng($work, $filename);
        
      }
      
      echo $id;
      
    }
	  
	  public function render($id) {
	    
	    $db = mysql_connect($this->mysql_host, $this->mysql_user, $this->mysql_pass);
      mysql_select_db($this->mysql_db, $db);

      $result = mysql_fetch_assoc(mysql_query("SELECT * FROM video WHERE id = '$id';"));
      
      $file_pref = basename($result['name'], '.mp4');
      
      mysql_close($db);

      $command_inputs = '';
      $command_filters = '';
      
      $data = json_decode($result['labels']);
      $l = sizeof($data);
      
      for($i = 0; $i < $l; $i++) {
        
        $filename = "./upload/$id/$i.png";
        
        $command_inputs .= ' -i ' . realpath($filename);
        if($l == 1) $command_filters .= "[0:v][1:v] overlay=" . $data[$i]->left . ":" . $data[$i]->top . ":enable='between(t," . $data[$i]->start . "," . $data[$i]->end . ")'";
        else if($i == 0) $command_filters .= "[0:v][1:v] overlay=" . $data[$i]->left . ":" . $data[$i]->top . ":enable='between(t," . $data[$i]->start . "," . $data[$i]->end . ")' [tmp1]";
        else if($i == $l - 1) $command_filters .= "; [tmp$i][" . ($i + 1) . ":v] overlay=" . $data[$i]->left . ":" . $data[$i]->top . ":enable='between(t," . $data[$i]->start . "," . $data[$i]->end . ")'";
        else $command_filters .= "; [tmp$i][" . ($i + 1) . ":v] overlay=" . $data[$i]->left . ":" . $data[$i]->top . ":enable='between(t," . $data[$i]->start . "," . $data[$i]->end . ")' [tmp" . ($i + 1) . "]";
        
      }
      
      if(file_exists(realpath("./upload/") . "/$file_pref-output.mp4")) unlink(realpath("./upload/") . "/$file_pref-output.mp4");
      
      file_put_contents(realpath('./upload/') . '/' . $id . '/output.txt', '');
      
      echo "./upload/$file_pref-output.mp4";
      
      exec('ffmpeg -i ' . realpath("./upload/$file_pref.mp4") . $command_inputs . ' -filter_complex "' . $command_filters . '" -strict -2 ' . realpath("./upload/") . "/$file_pref-output.mp4 && php " . realpath('./index.php') . ' ' . $id);
	    
	  }
	  
		public function uploadFile() {
			$uploaddir = 'upload/';
			$uploadfile = $uploaddir . basename($_FILES['uploadFile']['name']);

			if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadfile)) {
			    header( 'Location: ?view=editor&path='.$uploadfile, true, 303 );
			} else {
			    return "Не удалось загрузиь файл!\n";
			}
		}

		public function uploadLink($link) {
			$uploaddir = 'upload/';
			$uploadfile = $uploaddir . basename($link);

			if (!copy($link, $uploadfile)) {
	   			return "Не удалось загрузить файл по ссылке.\n";
			} else {
				header( 'Location: ?view=editor&path='.$uploadfile, true, 303 );
			}
		}

		public function uploadYoutube($link,$type) {
			$uploaddir = 'upload/';
			$type = substr($type, 6);
			$uploadfile = $uploaddir . "youtube".date("d-m-Y-h-i-s").".".$type;

			if (!copy($link, $uploadfile)) {
	   			return "Не удалось загрузить файл с Youtube.\n";
			} else {
				header( 'Location: ?view=editor&path='.$uploadfile, true, 303 );
			}
		}

    public function decToTime($dec) {
      $decM = $dec / 60;
      $decCeil = floor($decM);
      $decS = ceil(($decM - $decCeil) * 60) . '';
      
      if(strlen($decS) == 1) $decS = '0' . $decS;
      return $decCeil . ':' . $decS;
    }

	}
