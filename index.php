<!DOCTYPE html>
<html lang="en">
<link href="index.css" type="text/css" rel="stylesheet">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Cloud Rank</title>
</head>
<body>
<div id="player1" class="aplayer"></div>
<script src="js/APlayer.min.js"></script>
<script src="js/player.js" defer="defer"></script>
<div class="tableAll">
    <h1 align='center' width="20%">云音乐排行</h1>
    <?php
    include "Table.php";
    $a = new \Table\Table('192.243.116.29:3306', 'root', 'mc0321..', 'test');
    $rowCount = $a->get_all_count();
    echo "<p id=\"total\">总计收录歌曲:{$rowCount}首(带有删除线则表示网易没有版权)</p>";
    ?>
    <table class="table" align='center' width="800px">
        <tr>
            <th width="auto">歌曲名</th>
            <th width="auto">歌手</th>
            <th width="60px">评论数</th>
            <th width='180px'>更新时间</th>
        </tr>
        <?php
        $pageNow = 1;
        if (!empty($_GET['page'])) {
            $pageNow = $_GET['page'];
        }
        $pageCount = $a->get_rank($pageNow, $rowCount);
        ?>
    </table>

    <?php
    echo "<br /></span><div align='center'>";

    if ($pageNow > 1) {
        $prePage = $pageNow - 1;
        echo "<a href='index.php?page=$prePage'>上一页</a>&nbsp;";
    }

    if ($pageNow < $pageCount) {
        $nextPage = $pageNow + 1;
        echo "<a href='index.php?page=$nextPage'>下一页</a>&nbsp;";
    }
    echo "当前页 {$pageNow}/共 {$pageCount} 页";
    echo "<br/><br/>"; ?>

    <!--<form action='index.php'>跳转到：<input type='text' name='page'/>-->
    <!--    <input type='submit' value='GO'></form>-->
    <?php
    for ($i = 1; $i <= $pageCount; $i++) {
		if(&i.equals($pageNow)){
			echo "<strong>$i</strong>";
		}
		else{
			echo "<a href='index.php?page=$i'>" . $i . "</a>&nbsp;";
		}
    }
    ?>
</div>
</div>
<div id="player1" class="aplayer"></div>
</body>
</html>