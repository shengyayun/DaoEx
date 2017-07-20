<?php

class DaoEx {

	/**
	 * 创建对应scheme下所有表的dao文件
	 */
	public function create($pdo, $scheme) {
		$sql = "select * from information_schema.tables where table_schema='{$scheme}'";
		$sth = $pdo->prepare($sql);
		$sth->execute();
		$rs = $sth->fetchAll(PDO::FETCH_ASSOC);
		$tables = [];
		foreach ($rs as $row) {
			$tables[ucwords($row["TABLE_SCHEMA"])][ucwords($row["TABLE_NAME"])] = $row;
		}

		$sql = "select * from information_schema.columns where table_schema='$scheme'";
		$sth = $pdo->prepare($sql);
		$sth->execute();
		$rs = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach ($rs as $row) {
			$tables[ucwords($row["TABLE_SCHEMA"])][ucwords($row["TABLE_NAME"])]["COLUMNS"][strtolower($row["COLUMN_NAME"])] = $row;
		}

		$folder = __DIR__ . '/Models';
		if (!is_dir($folder)) {
			$this->deldir($folder);
		}
		foreach ($tables as $scheme => $tables) {
			foreach ($tables as $table => $detail) {
				$this->mkdirs("{$folder}/{$scheme}/");
				$content = "<?php
namespace DaoEx\\{$scheme};

/**
 * {$detail['TABLE_COMMENT']}
 */
class $table{
";

				$primary = false;
				$auto = [];
				foreach ($detail['COLUMNS'] as $column => $current) {
					$content .= "	/**
	* {$current['COLUMN_COMMENT']}
	* @var {$current['COLUMN_TYPE']}
	*/
	public \${$column};

";
					if ($current['COLUMN_KEY'] == 'PRI') {
						$primary = $column;
					}
					if ($current['EXTRA'] == 'auto_increment')
					{
						$auto[] = $column;
					}
				}

				$content .= "	/**
	* primary key
	* @var string
	*/
	public \$primary = '{$primary}';

";

				$content .= "	/**
	* auto_increment
	* @var array
	*/
	public \$auto = [" . (count($auto) == 0 ? '' : ("'" . implode("', '", $auto) . "'")) . "];

";



				$content .= "}
";
				file_put_contents("{$folder}/{$scheme}/{$table}.php", $content);
			}
		}
	}

	/**
	 * 递归创建目录
	 */
	private function mkdirs($dir, $mode = 0755) {
		if (is_dir($dir) || @mkdir($dir, $mode)) {
			return TRUE;
		}

		if (!$this->mkdirs(dirname($dir), $mode)) {
			return FALSE;
		}

		return @mkdir($dir, $mode);
	}

	/**
	 * 删除目录
	 */
	private function deldir($dir) {
		//先删除目录下的文件：
		$dh = opendir($dir);
		while ($file = readdir($dh)) {
			if ($file != "." && $file != "..") {
				$fullpath = $dir . "/" . $file;
				if (!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					$this->deldir($fullpath);
				}
			}
		}

		closedir($dh);
		//删除当前文件夹：
		if (rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}
}

$ex = new DaoEx();
//这里改成你的数据库配置
$pdo = new PDO("mysql:host=127.0.0.1;port=3306;charset=utf8", 'root', 'xxxxxxx');
$ex->create($pdo, 'test');
