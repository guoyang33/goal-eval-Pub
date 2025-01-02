<?php
/*
 * Log all ads YT video links
 * Group: Training Strategy
 */

$game_category = array(
    array('category' => '角色扮演', 'games' => array(
        array('name_zh' => '原神', 'ytv_id' => '_DGSLI29W7s'),          // 2023-10-23 更新
        // array('name_zh' => '天堂W', 'ytv_id' => '1uVcM7fsHoM'),      // 2023-10-23 更新：應上頭要求，暫時移除
        array('name_zh' => '勝利女神：妮姬', 'ytv_id' => 'dyoRWt9yx2I'),
        array('name_zh' => 'RO：愛如初見', 'ytv_id' => 'Mcvsl3SkggE')
    )),
    array('category' => '紙牌', 'games' => array(
        // array('name_zh' => '爐石戰記', 'ytv_id' => 'wnb2qVzs4dA'),   // 2023-10-17 更新：連結失效
        array('name_zh' => '爐石戰記', 'ytv_id' => 'JuDfah98WYk'),      // 2023-10-23 更新
        array('name_zh' => '遊戲王：決鬥聯盟', 'ytv_id' => '4Y1Ldb-zDyk'),
        array('name_zh' => 'Fate/Grand Order', 'ytv_id' => 'SnhGPEIzrBU')
    )),
    array('category' => '動作', 'games' => array(
        array('name_zh' => '傳說對決', 'ytv_id' => 'PQS9B2ZRR9E'),
        array('name_zh' => '崩壞3rd', 'ytv_id' => '4Xz_psIlPzQ'),
        array('name_zh' => '決勝時刻Mobile', 'ytv_id' => 'SgG8pW6QVno')
    )),
    array('category' => '模擬', 'games' => array(
        array('name_zh' => '三國志霸道', 'ytv_id' => '5fs6GgbnJVY'),
        array('name_zh' => '戀與製作人', 'ytv_id' => 'YZtbV3riieM'),
        array('name_zh' => '叫我大掌櫃', 'ytv_id' => 'DwihncTD46M')
    )),
    array('category' => '街機', 'games' => array(
        array('name_zh' => '跑跑薑餅人', 'ytv_id' => 'CSTiAbGxAZ8'),
        array('name_zh' => '不算英雄', 'ytv_id' => 'pnABaETg3xo'),
        array('name_zh' => 'Minecraft', 'ytv_id' => 'SUG367PW75s')
    )),
    array('category' => '音樂', 'games' => array(
        array('name_zh' => '世界計畫', 'ytv_id' => 'zHS9kJU-zfo'),
        array('name_zh' => 'Bang Dream', 'ytv_id' => '8Aqp4f8cEmk'),
        array('name_zh' => 'IDOLiSH7-偶像星願', 'ytv_id' => 'OzfrWGOec1c')
    )),
    array('category' => '博奕', 'games' => array(
        array('name_zh' => '星城Online', 'ytv_id' => 'cv8wBOvvi1c'),
        array('name_zh' => '明星三缺一', 'ytv_id' => '4S0u-CdCCzs'),
        array('name_zh' => '老子有錢', 'ytv_id' => '8UYsp2yrccg')
    )),
    array('category' => '賽車', 'games' => array(
        array('name_zh' => '跑跑卡丁車Rush+', 'ytv_id' => 'hei4YEXoESs'),
        array('name_zh' => '狂野飆車', 'ytv_id' => 'cLZtrlt6K5I'),
        array('name_zh' => '極速領域：逐夢同行', 'ytv_id' => '2BUhVqSx3-4')
    )),
    array('category' => '策略', 'games' => array(
        array('name_zh' => '末日喧囂', 'ytv_id' => 'bsClKdW6MPo'),
        array('name_zh' => '王國紀元', 'ytv_id' => 'GzQ1XpmcaXU'),
        array('name_zh' => '三國志戰略版', 'ytv_id' => 'XZbGVF25XRQ')
    ))
);

$yt_link = 'https://www.youtube.com/embed/%1$s';

foreach ($game_category as $category) {
    echo "======{$category['category']}類遊戲======\n";
    foreach ($category['games'] as $yt_obj) {
        echo '遊戲：' . $yt_obj['name_zh'] . "\n";
        echo sprintf($yt_link, $yt_obj['ytv_id']) . "\n" . "\n";
    }
}