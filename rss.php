<?php
    /** Данные для подключения к БД */
    $__server = 'localhost';
    $__user = 'root';
    $__password = 'minister0d';
    $__db = 'db';

    /** Подключение к БД */
    $sql_handler = mysqli_connect($__server, $__user, $__password, $__db);
    mysqli_set_charset($sql_handler, 'utf-8');

    /* Получаем из БД: */
    $sql_query = mysqli_query($sql_handler,
        'SELECT 
            rooms.id,           -- id записи, 
            rooms.type_id,      -- тип квартиры,
            rooms.title,        -- заголовок, 
            rooms.country_id,   -- страну, 
            rooms.city_id,      -- город, 
            rooms.district_id,  -- район, 
            rooms.street_id,    -- улицу, 
            rooms.description,  -- описание,
            rooms.image,        -- путь к изображению, 
            rooms.date_added    -- дату добавления
        FROM rooms
        ORDER BY re_data.date_added DESC LIMIT 10'
    );
    $sql_result = $sql_query->fetch_all(MYSQL_ASSOC);

    /** Начинаем оформление RSS документа
     */

    header("Content-Type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>

<rss version="2.0">
    <channel>
        <title>Агенство недживимости "Квартирант"</title>
        <link>ссылка на новость</link>
        <description>Описание</description>
        <language>Русский</language>
<?php

/**
 * Выводим данные о всех найденных квартирах в формате RSS
 */
if ($sql_result) {

    $i = 0;

    foreach ($sql_result as $key => $result) {
        $item[type] = ($result[title]) ?
             'Тип:' . $result[title] . ' ' : null;
        $item[city] = ($result[city]) ? 
            'Тип:' . $result[city] . ' ' : null;
        $item[district] = ($result[district]) ? 
            'Тип:' . $result[district] . ', ' : null;
        $item[street] = ($result[street]) ? 
            'Тип:' . $result[street] . ' ' : null;
        $item[text] = ($result[text]) ? 
            'Тип:' . $result[description] : null;
        $item[image] = substr($result[image], strpos($result[image], 's:33:"img') + 6, 33);
        $item[url] = ($item[image]) ? 
            '<enclosure url="http://pfb7988.bget.ru/img/data/'.$item[image].'" length="2000" type="image/jpg" />' : null;
        $item[date] = date('D, d M Y H:i:s T', strtotime($res[date_added]));

echo <<<TEXT
    <item>
        <title>
            $item[title]
        </title>
        <description>
            $item[type] $item[country] $item[city] $item[district] $item[street]
            $item[description]
        </description>
        <link>
            http://rooms.com/news/
        </link>
        <guid isPermaLink="true">
            http://rooms.com/news/
        </guid>
        <pubDate>
            $item[date]
        </pubDate>
    </item>
TEXT;
    }

    }
?>
    </channel>
</rss>