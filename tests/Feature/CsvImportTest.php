<?php

namespace Tests\Feature;

use App\Models\Responden;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CsvImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    private function admin(): User
    {
        return User::where('email', 'admin@admin.com')->firstOrFail();
    }

    private function fixtureUpload(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'sample-respondens.csv',
            file_get_contents(base_path('tests/fixtures/sample-respondens.csv'))
        );
    }

    public function test_csv_import_happy_path_inserts_rows_and_deletes_upload(): void
    {
        // Step 1: upload the CSV and get the field-mapping screen
        $parseResponse = $this->actingAs($this->admin())
            ->post(route('admin.respondens.parseCsvImport'), [
                'csv_file' => $this->fixtureUpload(),
                'header' => 1,
                'model' => 'Responden',
            ]);

        $parseResponse->assertStatus(200);
        $parseResponse->assertViewIs('csvImport.parseInput');
        $parseResponse->assertViewHas('headers', ['nama', 'kode', 'usia']);

        $filename = $parseResponse->viewData('filename');
        $this->assertFileExists(storage_path('app/csv_import/'.$filename));

        // Step 2: confirm the field mapping and run the import
        $processResponse = $this->actingAs($this->admin())
            ->post(route('admin.respondens.processCsvImport'), [
                'filename' => $filename,
                'hasHeader' => 1,
                'modelName' => 'Responden',
                'redirect' => '/admin',
                'fields' => ['nama', 'kode', 'usia'],
            ]);

        $processResponse->assertRedirect('/admin');
        $processResponse->assertSessionHas('message');

        $this->assertSame(2, Responden::count());
        $this->assertDatabaseHas('respondens', ['nama' => 'Siti Aminah', 'kode' => 'RSP-001', 'usia' => 28]);
        $this->assertDatabaseHas('respondens', ['nama' => 'Dewi Lestari', 'kode' => 'RSP-002', 'usia' => 32]);

        // The uploaded file is cleaned up after a successful import
        $this->assertFalse(File::exists(storage_path('app/csv_import/'.$filename)));
    }

    public function test_csv_import_rejects_model_outside_whitelist(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.respondens.parseCsvImport'), [
                'csv_file' => $this->fixtureUpload(),
                'header' => 1,
                'model' => 'User',
            ])
            ->assertStatus(422);

        $this->actingAs($this->admin())
            ->post(route('admin.respondens.processCsvImport'), [
                'filename' => 'whatever.csv',
                'hasHeader' => 1,
                'modelName' => 'User',
                'redirect' => '/admin',
                'fields' => ['name'],
            ])
            ->assertStatus(422);
    }

    public function test_csv_import_requires_authentication(): void
    {
        $this->post(route('admin.respondens.parseCsvImport'), [])
            ->assertRedirect(route('login'));
    }
}
