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
//
//    private $servername;
//    private $username;
//    private $password;
//    private $dbname;


//        echo "连接成功";

    private $pageSize = 20;
//    static $rowCount = 0; //获取
    private $conn;

//        $pageNow = 1;

    function __construct($servername, $username, $password, $dbname)
    {
        // 创建连接
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        $this->conn->query("set names utf8");
        // 检测连接
        if ($this->conn->connect_error) {
//            die("连接失败: " . $conn->connect_error);
            die("数据库君已经使用了洪荒之力了...");
        }
    }


    function get_rank($pageNow, $rowCount)
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
            $js_change = "music:[";


//            echo $this->get_music_info(60001);
//            exit();
            // 输出每行数据
//            error_reporting(0);
            while ($row = $result->fetch_assoc()) {
                ////去除令人不适的歌曲...
                if ($row['songid'] == 4466775) {
                    continue;
                }


                $music_id = $row["songid"];
                //$music_info = $this->get_music_info($music_id);


                $mp3_reurl = "http://litools.licoy.cn/api/163music.php?id={$music_id}";
                $music_info = $this->curl_get($mp3_reurl);
                $music_info = json_decode($music_info, true);
                $mp3_pic = $music_info["pic"];
                $mp3_url = $music_info["url"];

                $mp3_name = strstr($row["songname"],"'")? str_replace("'","\\'",$row["songname"]):$row["songname"];
                $mp3_artist = strstr($row["artist"],"'")? str_replace("'","\\'",$row["artist"]):$row["artist"];
//                echo check_single_yin($row["songname"]);
                if (!empty($mp3_url)) {
                    echo "<tr style='border:1px solid;'><td style='word-wrap:break-word;' width='300px' align='center'><a style='text-decoration: none' target='_blank' href='http://music.163.com/#/song?id={$row['songid']}'>{$row["songname"]}</a></td>"
                        . "<td align='center'>{$row["artist"]}</td>"
                        . "<td align='right'>{$row["count"]}</td>"
                        . "<td align='center'>{$row["modifytime"]}</td></tr>";
                    $js_change .= "{title:'$mp3_name',author:'$mp3_artist',url:'$mp3_url',pic:'$mp3_pic'},";

                } else {
                    echo "<tr style='border:1px solid;'><td style='word-wrap:break-word;' width='300px' align='center'><a style='text-decoration: line-through' target='_blank' href='http://music.163.com/#/song?id={$row['songid']}'>{$row["songname"]}</a></td>"
                        . "<td align='center'>{$row["artist"]}</td>"
                        . "<td align='right'>{$row["count"]}</td>"
                        . "<td align='center'>{$row["modifytime"]}</td></tr>";
                }
            }
//                echo "<br> songid: " . $row["songid"] . " - songname: " . $row["songname"] . " - artist: " . $row["artist"] . " - modifytime: " . $row["modifytime"];

            $js_change = substr($js_change, 0, strlen($js_change) - 1);
            $js_change .= "]";

            $myfile = fopen("js/player.js", "r") or die("Unable to open file!");
            $js_str = fread($myfile, filesize("js/player.js"));

            fclose($myfile);

            //echo "js_str:</br>" . $js_str . "</br></br>";
            //echo "js_change:</br>" . $js_change . "</br></br>";
            $js_str1 = preg_replace('/music:.+/', $js_change, $js_str);
            //           echo "js_str1:</br>" . $js_str1 . "</br></br>";
//            exit();
            $myfile = fopen("js/player.js", "w") or die("Unable to open file!");
            fwrite($myfile, $js_str1);
            fclose($myfile);

//            echo $this->get_music_url(60001);
        }
        $this->conn->close();
//        echo "<h1 align='center'>网易云音乐评论数排行榜</h1>";
//        echo "</table>";
        return $pageCount;
    }

    function get_all_count()
    {
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

    function curl_get($url)
    {
        $refer = "http://music.163.com/";
        $header[] = "Cookie: " . "appver=1.5.0.75771;";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, $refer);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    function get_music_info($music_id)
    {
        $url = "http://music.163.com/api/song/detail/?id=" . $music_id . "&ids=%5B" . $music_id . "%5D";
        return $this->curl_get($url);
    }


    function check_single_yin($str){
        if(strstr($str,"\'")){
            echo $str."存在单引号";
        }
        else{}
    }
//    function get_music_url($music_id)
//    {
//        $url = "http://music.163.com/api/song/detail/?id=" . $music_id . "&ids=%5B" . $music_id . "%5D";
//        $music_info = json_decode($this->curl_get($url), true);
//        return $music_info["songs"][0]["mp3Url"];
//    }
}

?>