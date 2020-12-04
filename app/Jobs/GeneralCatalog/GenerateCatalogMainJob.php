<?php

namespace App\Jobs\GeneralCatalog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateCatalogMainJob extends AbstractJob
{

    /**
     * Execute the job.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     */
    public function handle()
    {
        $this->debug('start');

        //сначала кешируум продукты
        GenerateCatalogCacheJob::dispatchNow();

        //затем создаем цепочку заданий формирования файлов с ценами
        $chainPrices = $this->getChainPrices();

        //основные подзадачи
        $chainMain = [
            new GenerateCategoriesJob, //генерация категорий
            new GenerateDeliveriesJob, //генерация способов доставки
            new GeneratePointsJob, //генерация пунктов выдачи
        ];

        //Подзадачи которые должны выполнится самыми последними
        $chainList = [
            new ArchiveUploadsJob,
            //Отправка уведомления стороннему сервису о том что можно скачать новый файл каталога товаров
            new SendPriceRequestJob,
        ];
        $chain = array_merge($chainPrices, $chainMain, $chainList);

        GenerateGoodsFileJob::withChain($chain)->dispatch();
//        GenerateGoodsFileJob::dispatch()->chain($chain);

        $this->debug('finish');
    }

    private function getChainPrices()
    {
        $result = [];
        $products = collect([1,2,3,4,5]);
        $fileNum = 1;

        foreach ($products->chunk(1) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk, $fileNum);
            $fileNum++;
        }
        return $result;
    }
}
