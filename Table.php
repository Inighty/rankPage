<?php
/**
 * Created by PhpStorm.
 * User: mcshr
 * Date: 2016/9/27
 * Time: 20:26
 */


namespace Table;

use mysqli;


class Table
{

    private $servername;
    private $username;
    private $password;
    private $dbname;


//        echo "连接成功";

    private $pageSize = 20;
//    static $rowCount = 0; //获取
    private $conn;
//        $pageNow = 1;

    function __construct($servername,$username,$password,$dbname)
    {
        // 创建连接
        $this->conn= new mysqli($servername, $username, $password, $dbname);
        $this->conn->query("set names utf8");
        // 检测连接
        if ($this->conn->connect_error) {
//            die("连接失败: " . $conn->connect_error);
            die("数据库君已经使用了洪荒之力了...");
        }
    }


    function get_rank($pageNow,$rowCount)
    {
//        $pageCount = 0; //共有多少页
//        $rowCount = $this->get_all_count();
        $pageCount = ceil($rowCount / $this->pageSize);

        $sql = "SELECT songid,songname,artist,count,modifytime FROM music_v2 ORDER BY count desc limit " . ($pageNow - 1) * $this->pageSize . "," . $this->pageSize;
        $result = $this->conn->query($sql);
        if ($this->conn->connect_error) {
//            die("连接失败: " . $conn->connect_error);
            die("数据库君已经使用了洪荒之力了...");
        }
//        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";


//        echo "<table table-layout:fixed;word-break:break-all; align='center' style='border:1px;border-collapse:collapse;'>";
//        echo "<tr><th>歌曲ID</th><th>歌曲名</th><th>歌手</th><th>评论数</th><th width='250px'>更新时间</th></tr>";
        if ($result->num_rows > 0) {
            // 输出每行数据
            while ($row = $result->fetch_assoc()) {
                echo "<tr style='border:1px solid;'><td style='word-wrap:break-word;' width='300px' align='center'><a style='text-decoration: none' target='_blank' href='http://music.163.com/#/song?id={$row['songid']}'>{$row["songname"]}</a></td>"
                    . "<td align='center'>{$row["artist"]}</td>"
                    . "<td align='right'>{$row["count"]}</td>"
                    . "<td align='center'>{$row["modifytime"]}</td></tr>";
//                echo "<br> songid: " . $row["songid"] . " - songname: " . $row["songname"] . " - artist: " . $row["artist"] . " - modifytime: " . $row["modifytime"];
            }
        }
        $this->conn->close();
//        echo "<h1 align='center'>网易云音乐评论数排行榜</h1>";
//        echo "</table>";
        return $pageCount;
    }

    function get_all_count(){
        $rowCount = 0;
        $sql = "select count(songid) from music_v2";
        $result = $this->conn->query($sql);
        if ($this->conn->connect_error) {
//            die("连接失败: " . $conn->connect_error);
            die("数据库君已经使用了洪荒之力了...");
        }
        if ($result) {
            $row = mysqli_fetch_row($result);
            $rowCount = $row[0];
        }
        return $rowCount;
    }

}

?>