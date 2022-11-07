<?php

namespace App\Console\Commands;

use App\Models\Body;
use App\Models\Car;
use App\Models\Color;
use App\Models\Engine;
use App\Models\Gear;
use App\Models\Generation;
use App\Models\Mark;
use App\Models\Model;
use App\Models\Transmission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParseXML extends Command
{
    const FILE = 'data.xml';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:xml {file? : Путь к локальному файлу XML. По умолчанию ./data.xml}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск парсера XML файла.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $file = $this->argument('file') ?? self::FILE;

        if (! file_exists(base_path($file))) {
            $this->error("File [{$file}] does not exist");

            return Command::INVALID;
        }

        try {
            $xml = json_decode(
                json_encode(
                    simplexml_load_file(base_path($file))
                ),
                true
            );
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return Command::INVALID;
        }

        $successCarIds = [];

        foreach ($xml['offers']['offer'] as $car) {
            DB::beginTransaction();

            try {
                $generation = Generation::firstOrCreate(
                    ['name' => $car['generation']],
                    ['external_id' => $car['generation_id']],
                );

                $body = Body::firstOrCreate(
                    ['name' => $car['body-type']],
                );

                $engine = Engine::firstOrCreate(
                    ['name' => $car['engine-type']]
                );

                $transmission = Transmission::firstOrCreate(
                    ['name' => $car['transmission']],
                );

                $gear = Gear::firstOrCreate(
                    ['name' => $car['gear-type']],
                );

                $mark = Mark::firstOrCreate(
                    ['name' => $car['mark']],
                );

                $model = Model::firstOrCreate(
                    [
                        'name' => $car['model'],
                        'mark_id' => $mark->id,
                    ],
                    [
                        'body_id' => $body->id,
                        'engine_id' => $engine->id,
                        'transmission_id' => $transmission->id,
                        'gear_id' => $gear->id,
                    ],
                );

                $color = Color::firstOrCreate(
                    ['name' => $car['color']],
                );

                $car = Car::firstOrCreate(
                    ['external_id' => $car['id']],
                    [
                        'year' => $car['year'],
                        'run' => $car['run'],
                        'model_id' => $model->id,
                        'generation_id' => $generation->id,
                        'color_id' => $color->id,
                    ]
                );

                $successCarIds[] = $car->id;

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
            }
        }

        Car::whereNotIn('external_id', $successCarIds)->delete();

        return Command::SUCCESS;
    }
}
