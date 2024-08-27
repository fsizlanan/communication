<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;
use App\Services\SpreadsheetService;
use App\Models\Product;
use App\Jobs\ProcessProductImage;
use Tests\TestCase;

class SpreadsheetServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake();
    }

    public function it_creates_products_from_valid_spreadsheet_data()
    {
        $filePath = 'path/to/valid/spreadsheet.xlsx';

        $spreadsheetService = new SpreadsheetService();

        $data = [
            ['product_code' => 'P123', 'quantity' => 10],
            ['product_code' => 'P124', 'quantity' => 15],
        ];

        $this->mock('importer')
            ->shouldReceive('import')
            ->with($filePath)
            ->andReturn($data);

        $spreadsheetService->processSpreadsheet($filePath);

        $this->assertDatabaseHas('products', ['code' => 'P123', 'quantity' => 10]);
        $this->assertDatabaseHas('products', ['code' => 'P124', 'quantity' => 15]);

        Bus::assertDispatched(ProcessProductImage::class, function ($job) {
            return $job->product->code === 'P123' || $job->product->code === 'P124';
        });
    }

    public function it_does_not_create_products_from_invalid_spreadsheet_data()
    {
        $filePath = 'path/to/invalid/spreadsheet.xlsx';

        $spreadsheetService = new SpreadsheetService();

        $data = [
            ['product_code' => '', 'quantity' => 10],
            ['product_code' => 'P125', 'quantity' => -5],
        ];

        $this->mock('importer')
            ->shouldReceive('import')
            ->with($filePath)
            ->andReturn($data);

        $spreadsheetService->processSpreadsheet($filePath);

        $this->assertDatabaseMissing('products', ['code' => 'P125', 'quantity' => -5]);
        $this->assertDatabaseMissing('products', ['code' => '', 'quantity' => 10]);

        Bus::assertNotDispatched(ProcessProductImage::class);
    }
}
