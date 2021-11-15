<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * 谷歌地图
 * Class mod_google_map
 * @package App\models
 */
class mod_google_map extends mod_model
{
    //终点数组
    public static function end_address_array($row)
    {
        $end_address_array = [];

        $row['stop_points_json'] = json_decode($row['stop_points_json'],true);
        $row['stop_points_json'] = empty($row['stop_points_json']) ?  [] : $row['stop_points_json'];

        foreach ($row['stop_points_json'] as $item)
        {
            foreach (['type', 'latlng', 'name'] as $_f)
            {
                if (empty($item[$_f])) continue;
            }

            //类型，1中间点数据 2修改目的地的当前位置 3修改的中间点
            if (empty($item['type']) || $item['type'] != 2) {
                $end_address_array[] = $item['name'];
            }
        }
        $end_address_array[] = $row['end_address'];
        return $end_address_array;
    }

    //返回坐标json值,lng=经度 lat=纬度
    public static function point($point)
    {
        return json_encode([ 'lng'=>(float)$point['lng'], 'lat'=>(float)$point['lat'] ]);
    }

    //获取行车路径
    public static function route($row)
    {
        if( !isset($row['start_y'], $row['start_x'], $row['end_y'], $row['end_x']) )
        {
            exit(__METHOD__.' required $row[start_y,start_x,end_y,end_x]');
        }

        $stop_point = [];
        if( !empty($row['stop_points_json']) )
        {
            $stop_points = json_decode($row['stop_points_json'],true);
            foreach ($stop_points as $_stop_point)
            {
                if(empty($_stop_point['type']) || $_stop_point['type']!=2)
                {
                    $stop_point_latlng = explode(',',$_stop_point['latlng']);
                    $stop_point = [ 'lat'=>(float)$stop_point_latlng[0], 'lng'=>(float)$stop_point_latlng[1] ];
                    if(array_filter($stop_point)==[])
                    {
                        $stop_point = [];
                    }
                }
            }
        }

        $start_point = [ 'lat'=>(float)$row['start_y'], 'lng'=>(float)$row['start_x'] ];
        $end_point = [ 'lat'=>(float)$row['end_y'], 'lng'=>(float)$row['end_x'] ];
        $google_map = [
            'start_point' => json_encode($start_point),
            'end_point' => json_encode($end_point),
            'stop_point' => json_encode($stop_point)
        ];

        return $google_map;
    }

    /**
     * 计算两点地理坐标之间的距离
     * @param  Decimal $longitude1 起点经度
     * @param  Decimal $latitude1  起点纬度
     * @param  Decimal $longitude2 终点经度
     * @param  Decimal $latitude2  终点纬度
     * @param  Int     $decimal    精度 保留小数位数
     * @return Decimal 返回N米 例 8454.56米
     */
    public static function get_distance($longitude1, $latitude1, $longitude2, $latitude2, $decimal=2)
    {
        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;

        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI /180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        return round($distance, $decimal); //四捨五入到小數點第2位
    }
}
