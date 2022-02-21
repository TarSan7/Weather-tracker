<?php

namespace App\Imports;

use App\Models\Weather;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class WeatherImport implements ToCollection
{
    /**
     * @var string
     */
    private $time;
    /**
     * @var int
     */
    private $day;
    /**
     * @var int
     */
    private $temp;
    /**
     * @var string
     */
    private $direction;
    /**
     * @var int
     */
    private $speed;

    /**
     * WeatherImport constructor.
     */
    public function __construct()
    {
        $this->time      = '00:00:00';
        $this->day       = 1;
        $this->temp      = 0;
        $this->direction = 'Ю-З';
        $this->speed     = 0;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $this->day = $rows[1][0] ?? $this->day;
        $this->temp = $rows[1][2] ?? $this->day;
        $this->direction = $rows[1][3] ?? $this->direction;
        $this->speed = $rows[1][4] ?? $this->speed;
        dd(date('H:i:s', strtotime( \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rows[1][1]))));
        for ($i = 1; $i < count($rows) - 1; $i++) {
            while (date('H:i:s', strtotime(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rows[$i][1]))) != date('H:i:s', strtotime($this->time))) {
                Weather::create([
                    'city_id' => 1,
                    'month' => 1,
                    'day_of_month' => $this->day,
                    'time' => $this->time,
                    'temperature' => ($this->temp + ($rows[$i + 1][2] ?? $this->temp)) / 2,
                    'direction' => $this->direction,
                    'speed' => ($this->speed + ($rows[$i + 1][4] ?? $this->speed)) / 2,
                ]);
                $this->time = date('H:i:s', strtotime($this->time . ' + 30 min'));
            }

            Weather::create([
                'city_id' => 1,
                'month' => 1,
                'day_of_month' => $this->day,
                'time' => $this->time,
                'temperature' => $this->temp,
                'direction' => $this->direction,
                'speed' => $this->speed
            ]);

            $this->day       = $rows[$i + 1][0] ?? $this->day;
            $this->temp      = $rows[$i + 1][2] ?? $this->day;
            $this->direction = $rows[$i + 1][3] ?? $this->direction;
            $this->speed     = $rows[$i + 1][4] ?? $this->speed;
            $this->time      = date('H:i:s', strtotime($this->time . ' + 30 min'));
        }
    }
}
