<?
// exit();
$_SERVER['DOCUMENT_ROOT'] = '/home/bitrix/ext_www/dfgfdgd.ru';
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/rggrg.php");
 // header("Content-Type: text/html; charset=utf-8");
?>
<?CModule::IncludeModule("iblock");
CModule::IncludeModule("highloadblock");
\Bitrix\Main\Loader::includeModule('sdsds.local');

function removeEmoji($text)
{
    $clean_text = "";

  
}


file_get_contents("https://ap". '/sendMessage?chat_id=ver&text=Запущен парсер');

class Mod {

    private $access_token;
    private $url = "https://m/method/";

    /**
     * Конструктор
     */
    public function __construct($access_token) {

        $this->access_token = $access_token;
    }

    /**
     * Делает запрос 
     * @param $method
     * @param $params
     */
    public function method($method, $params = null) {

        $p = "";
        if( $params && is_array($params) ) {
            foreach($params as $key => $param) {
                $p .= ($p == "" ? "" : "&") . $key . "=" . urlencode($param);
            }
        }
        $response = file_get_contents($this->url . $method . "?" . ($p ? $p . "&" : "") . "access_token=" . $this->access_token."&v=5.101");

        if( $response ) {
            return json_decode($response);
        }
        return false;
    }
}





$res = $strEntityDataClassProv::getList(array(
     // 'select' => ['ID'],
     'order' => array('UF_DATE' => 'ASC'),
     'filter' => ['UF_ACTIVE' => 1, '!UF_DATE' => date('d.m.Y')],
     // 'limit' => 2500
));

$a = 0; //Подсчет ссылок
$z = 0; // Подсчте ссылок с товарами
$addedAll = 0;

while ($arItem = $res->fetch()) {
    $added = 0;
    // echo '<pre>';
    // print_r($arItem['UF_LINK']);
    // $ids[] = $arItem['ID']; //UF_PROVIDER
    $arDomain = explode('/', $arItem['UF_LINK']);
    $domain = trim(end($arDomain));
    // $domain = '9551656';

    // echo end($arDomain)."\n";
    $params = array(
        // "domain" => end($arDomain),
        "filter" => 'owner',
        "count" => 100
    );
    // echo $arItem['ID'];

    preg_match_all('/club\d/', $domain, $matches, PREG_SET_ORDER, 0);

    if($matches){
        $params['owner_id'] = str_replace('club', '-', $domain);
    }elseif(strpos($domain, 'public') && strpos($domain, 'public') < 2 ){
        $params['owner_id'] = str_replace('public', '-', $domain);
    }else{
        $params['domain'] = $domain;
    }

    // print_r($params);
    // exit();
 // $t = k->method("market.get", $params_3);
    $result =';
    // print_r($result);
    // exit();
    
    $vkIds = [];
    $vkIds[] = $arItem['UF_ID_VK'];
    // echo "<pre style='text-align:left'>";
    // 
    // UF_COMMENT
    if($result->error){
        if($result->error->error_msg == 'User was deleted or banned' || $result->error->error_msg == 'Access denied: group is blocked'
    ){
            usleep(400000);
            continue;
        }
    	
        
        echo $arItem['ID'].'_'.$result->error->error_msg."\n";

        if($result->error->error_msg != 'This profile is private' && 
            // $result->error->error_msg != 'User was deleted or banned' &&
            $result->error->error_msg != 'This profile is private' &&
            $result->error->error_msg != 'Access denied: this wall available only for community members'){
                file_get_contents("https://a.'-'.$result->error->error_msg);
                exit();
        }else{
            $strEntityDataClassProv::update($arItem['ID'], ['UF_DATE' => date('d.m.Y H:i:s'), 'UF_ACTIVE' => '', 'UF_COMMENT' => 'NO '.$result->error->error_msg]);
        }
        usleep(400000);
        continue;

       
    }

    
    foreach ($result->response->items as $key => $item) {
        $[] = $item->id;
        if($item->is_pinned == 1){//закрепленый
            continue;
        }

        if($item->date < time() - 60*60*24*7){
            continue;
        }

        if(!$item->attachments && !$item->text){
            continue;
        }

        if($item->id <= $arItem['UF_ID_VK']){
            continue;
        }

        $text = removeEmoji($item->text);

        $photos = [];
        foreach ($item->attachments as $attach) {
            $photo = '';
            if($attach->type == 'photo'){
                if($attach->photo->photo_807){
                    $photo = $attach->photo->photo_807;
                }elseif($attach->photo->photo_604){
                    $photo = $attach->photo->photo_604;
                }else{
                    $photo = $attach->photo->photo_130;
                }


                if(!$photo){
                    $arSizes = [];
                    foreach ($attach->photo->sizes as $size) {
                        $arSizes[$size->type] = $size->url;
                    }

                    if($arSizes['z']){
                        $photo = $arSizes['z'];
                    }elseif($arSizes['y']){
                        $photo = $arSizes['y'];
                    }else{
                        $photo = $arSizes['x'];
                    }
                    
                }
                
            }

            // print_r($arSizes);


            if($photo){
                $photos[] = $photo;
            }
            
        }

        // continue;
        // print_r($photos);
        // echo '<hr>';

        if(!$photos){
            // die($arItem['ID'].'  NO PHOTO  '.str_replace('m.', '', $arItem['UF_LINK']).'?w=wall'.$item->owner_id.'_'.$item->id);
        }
        
        $arFields = [
            'UF_TEXT' => $text,
            'UF_IMAGE' => implode(';', $photos),
            'UF_PROVIDER' => $arItem['UF_PROVIDER'],
            'UF_ID_VK' => $item->id,
            'UF_LINK' => str_replace('m.', '', $arItem['UF_LINK']).'?w=wall'.$item->owner_id.'_'.$item->id,
            'UF_DATE_POST' => date('d.m.Y H:i:s', $item->date)

        ];
        
        $res2 = $strEntityDataClass::add($arFields);

        $added++;
        $isProducts = true; //Отметка что товары были
       
            // echo "<pre style='text-align:left'>";
            // print_r($arFields);
            // echo "</pre>";
        
        
        // exit();
    }
    //обновляем 
    $strEntityDataClassProv::update($arItem['ID'], [
            'UF_DATE' => date('d.m.Y H:i:s'), 
            'UF_ID_VK' => max($),
            'UF_ACTIVE' => 1, 
            'UF_COMMENT' => '',
            'UF_COUNT' => $arItem['UF_COUNT'] + $added
        ]);

    echo $arItem['ID'].'  '.$added."\n";



    usleep(400000);
    $a++;

    if($isProducts){
        $z++;
    }
    $addedAll += $added;
}
