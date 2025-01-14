<?php
/* 
 * 設定新的ios使用者清單
 * 
*/

define('VALID_EXP_ID_LIST', array(
    // 課服組用: 2023-12-05 新增
    // 策略組
    'I1122190' => array('default_password' => '123456'),
    'I1122191' => array('default_password' => '123456'),
    'I1122192' => array('default_password' => '123456'),
    'I1122193' => array('default_password' => '123456'),
    'I1122194' => array('default_password' => '123456'),
    // 目標組
    'I1122290' => array('default_password' => '123456'),
    'I1122291' => array('default_password' => '123456'),
    'I1122292' => array('default_password' => '123456'),
    'I1122293' => array('default_password' => '123456'),
    'I1122294' => array('default_password' => '123456'),

    // 從Android移過來的: 2023-12-05
    'I1121219' => array('default_password' => '112061055'),
    // 從Android移過來的: 2023-12-18
    'I1122228' => array('default_password' => '112021128'),

    // Day 1: 2023-11-21
    'I1121101' => array('default_password' => '112011052'),
    'I1121102' => array('default_password' => '112042016'),
    'I1121103' => array('default_password' => '112015214'),
    'I1121104' => array('default_password' => '112052030'),
    'I1121105' => array('default_password' => '112015100'),
    'I1121106' => array('default_password' => '112031114'),
    'I1121201' => array('default_password' => '112015208'),
    'I1121202' => array('default_password' => '112016100'),
    'I1121203' => array('default_password' => '112015225'),
    'I1122101' => array('default_password' => '112043038'),
    'I1122102' => array('default_password' => '112043014'),
    'I1122103' => array('default_password' => '112014065'),
    'I1122104' => array('default_password' => '112055016'),
    'I1122105' => array('default_password' => '112042012'),
    'I1122201' => array('default_password' => '112042102'),
    'I1122202' => array('default_password' => '112034019'),
    'I1122203' => array('default_password' => '112015119'),
    'I1122204' => array('default_password' => '112015077'),
    'I1122205' => array('default_password' => '112014129'),
    // Day 2: 2023-11-22
    'I1121107' => array('default_password' => '112043012'),
    'I1121108' => array('default_password' => '112014011'),
    'I1121109' => array('default_password' => '112015115'),
    'I1121110' => array('default_password' => '112061057'),
    'I1121111' => array('default_password' => '112015212'),
    'I1121112' => array('default_password' => '112015205'),
    'I1121204' => array('default_password' => '112018044'),
    'I1121205' => array('default_password' => '112018072'),
    'I1121206' => array('default_password' => '112019083'),
    'I1121207' => array('default_password' => '112031005'),
    'I1121208' => array('default_password' => '112043037'),
    'I1121209' => array('default_password' => '112052012'),
    'I1122106' => array('default_password' => '112018031'),
    'I1122107' => array('default_password' => '112015118'),
    'I1122108' => array('default_password' => '112015028'),
    'I1122109' => array('default_password' => '112015029'),
    'I1122110' => array('default_password' => '112015198'),
    'I1122111' => array('default_password' => '112015206'),
    'I1122112' => array('default_password' => '112015058'),
    'I1122113' => array('default_password' => '112031106'),
    'I1122114' => array('default_password' => '112031102'),
    'I1122115' => array('default_password' => '112031037'),
    'I1122116' => array('default_password' => '112018084'),
    'I1122206' => array('default_password' => '112018073'),
    'I1122207' => array('default_password' => '112018059'),
    'I1122208' => array('default_password' => '112025004'),
    'I1122209' => array('default_password' => '112034009'),
    'I1122210' => array('default_password' => '112012130'),
    'I1122211' => array('default_password' => '112021031'),
    'I1122212' => array('default_password' => '112021002'),
    'I1122213' => array('default_password' => '112021144'),
    'I1122214' => array('default_password' => '112036019'),
    'I1122215' => array('default_password' => '112025028'),
    'I1122216' => array('default_password' => '1234'),
    'I1121113' => array('default_password' => '1234'),
    // Day 3: 2023-11-23
    'I1121114' => array('default_password' => '112018027'),
    'I1122117' => array('default_password' => '112018024'),
    'I1122118' => array('default_password' => '112061044'),
    'I1122119' => array('default_password' => '112043039'),
    'I1121115' => array('default_password' => '112015031'),
    'I1121116' => array('default_password' => '112051116'),
    'I1122120' => array('default_password' => '112018057'),
    'I1121117' => array('default_password' => '112043107'),
    'I1122217' => array('default_password' => '112043100'),
    'I1121210' => array('default_password' => '112018037'),
    'I1122218' => array('default_password' => '112015017'),
    'I1122219' => array('default_password' => '112015022'),
    'I1122220' => array('default_password' => '112015071'),
    'I1122221' => array('default_password' => '112031009'),
    'I1121211' => array('default_password' => '112043113'),
    'I1122109' => array('default_password' => '112015029'),
    'I1122121' => array('default_password' => '112061041'),
    // Day 4: 2023-11-24
    'I1121118' => array('default_password' => '112031045'),
    'I1121119' => array('default_password' => '112015131'),
    'I1121120' => array('default_password' => '112015084'),
    'I1121121' => array('default_password' => '112036017'),
    'I1121212' => array('default_password' => '112012036'),
    'I1121213' => array('default_password' => '112012033'),
    'I1121214' => array('default_password' => '112035034'),
    'I1121215' => array('default_password' => '112021006'),
    'I1121216' => array('default_password' => '112021033'),
    'I1121217' => array('default_password' => '112035077'),
    'I1122122' => array('default_password' => '112014083'),
    'I1122123' => array('default_password' => '112021131'),
    'I1122220' => array('default_password' => '112015071'),
    'I1122222' => array('default_password' => '112014049'),
    'I1122223' => array('default_password' => '112031097'),
    'I1122224' => array('default_password' => '112018088'),
    'I1122225' => array('default_password' => '112021132'),
    'I1122226' => array('default_password' => '112021028'),
    // Day 5: 2023-11-26
    'I1121122' => array('default_password' => '112061004'),
    'I1121218' => array('default_password' => '112015068'),
    'I1122227' => array('default_password' => '112021050'),
));

?>