<?php
namespace App\Imports;

use App\Models\Goods;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DataImport implements ToCollection
{
    /**
     * @param Collection $collection
     *
     */
    public function collection(Collection $collection)
    {
        $img   = $collection[0][0];
        $name  = $collection[0][1];
        $type  = $collection[0][2];
        $demi  = $collection[0][3];
        $one   = $collection[0][4];
        $price = $collection[0][5];
        $disk  = \Storage ::disk('qiniu');
        set_time_limit(20000);
        foreach ($collection as $key => $item){
            $img     = $data['img'] = $item[0] ?? $img;
            $file    = "./yqd/img/$img";
            $newname = "mall/goods/$img";
            $disk -> put($newname,file_get_contents($file));
            $type = $data['type'] = $item[2] ?? $type;
            $name = $data['name'] = $item[1] ?? $name;
            $demi = explode('*',$item[3]) ?? $demi;
            array_walk($demi,function (&$dem){
                $dem = intval($dem);
            });
            $data['long']   = $demi[0] ?? null;
            $data['weith']  = $demi[1] ?? null;
            $data['height'] = $demi[2] ?? null;
            $data['demi']   = $item[2];
            $one            = $data['one'] = $item[4] ?? $one;
            $price          = $data['price'] = $item[5] ?? $price;
            $data['img']    = $disk -> url($newname);
            $tmp            = Goods :: create($data);
            echo($tmp -> id);
        }
    }
}
