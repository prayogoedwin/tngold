<?php

namespace Database\Seeders;

use App\Models\HargaEmas\HargaEmasHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HargaEmasHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datajson = '{
  "currency": "IDR",
  "unit": "1 gram",
  "purity": "99.99% (24K)",
  "history": [
    {
      "date": "2026-11-17",
      "prices": {
        "Antam": 1545000,
        "UBS Gold": 1512000,
        "Galeri 24": 1518000,
        "Lotus Archi": 1510000,
        "King Halim": 1508000
      }
    },
    {
      "date": "2026-11-16",
      "prices": {
        "Antam": 1542000,
        "UBS Gold": 1510000,
        "Galeri 24": 1515000,
        "Lotus Archi": 1508000,
        "King Halim": 1505000
      }
    },
    {
      "date": "2026-11-15",
      "prices": {
        "Antam": 1542000,
        "UBS Gold": 1509000,
        "Galeri 24": 1515000,
        "Lotus Archi": 1507000,
        "King Halim": 1505000
      }
    },
    {
      "date": "2026-11-14",
      "prices": {
        "Antam": 1538000,
        "UBS Gold": 1505000,
        "Galeri 24": 1511000,
        "Lotus Archi": 1503000,
        "King Halim": 1501000
      }
    },
    {
      "date": "2026-11-13",
      "prices": {
        "Antam": 1531000,
        "UBS Gold": 1498000,
        "Galeri 24": 1504000,
        "Lotus Archi": 1495000,
        "King Halim": 1494000
      }
    },
    {
      "date": "2026-11-12",
      "prices": {
        "Antam": 1535000,
        "UBS Gold": 1502000,
        "Galeri 24": 1508000,
        "Lotus Archi": 1499000,
        "King Halim": 1497000
      }
    },
    {
      "date": "2026-11-11",
      "prices": {
        "Antam": 1529000,
        "UBS Gold": 1496000,
        "Galeri 24": 1501000,
        "Lotus Archi": 1493000,
        "King Halim": 1490000
      }
    },
    {
      "date": "2026-11-10",
      "prices": {
        "Antam": 1524000,
        "UBS Gold": 1490000,
        "Galeri 24": 1496000,
        "Lotus Archi": 1488000,
        "King Halim": 1485000
      }
    },
    {
      "date": "2026-11-09",
      "prices": {
        "Antam": 1520000,
        "UBS Gold": 1487000,
        "Galeri 24": 1492000,
        "Lotus Archi": 1484000,
        "King Halim": 1482000
      }
    },
    {
      "date": "2026-11-08",
      "prices": {
        "Antam": 1520000,
        "UBS Gold": 1487000,
        "Galeri 24": 1492000,
        "Lotus Archi": 1484000,
        "King Halim": 1482000
      }
    }
  ]
}';


        $data = json_decode($datajson, true);
        // Antam = 1, UBS Gold = 2, Galeri 24 = 3, Lotus Archi = 4, King Halim = 5


        //reverse the history array to start from the oldest date
        $data['history'] = array_reverse($data['history']);

        foreach ($data['history'] as $history) {
            $date = $history['date'];
            foreach ($history['prices'] as $emas_asal => $harga_beli) {
                $emas_id = match($emas_asal) {
                    'Antam' => 1,
                    'UBS Gold' => 2,
                    'Galeri 24' => 3,
                    'Lotus Archi' => 4,
                    'King Halim' => 5,
                    default => null,
                };
                if ($emas_id) {
                    // Insert into database or perform necessary actions
                    // Example: HargaEmasHistory::create([...]);
                    HargaEmasHistory::create([
                        'harga_emas_id' => $emas_id,
                        'tanggal' => $date,
                        'harga_beli' => $harga_beli,
                        'created_by' => 1,
                        'updated_by' => 1
                    ]);
                }
            }
        }
    }
}
