<?php

use App\Constants\Status;
use App\Models\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $supported = config('toapp_languages.supported', []);

        foreach ($supported as $code => $meta) {
            $existing = Language::query()->where('code', $code)->first();
            if ($existing) {
                $existing->name = $meta['name'];
                if ($code === 'en' && ! Language::query()->where('is_default', Status::ENABLE)->exists()) {
                    $existing->is_default = Status::ENABLE;
                }
                $existing->save();
                continue;
            }

            Language::query()->create([
                'name' => $meta['name'],
                'code' => $code,
                'is_default' => $code === 'en' ? Status::ENABLE : Status::DISABLE,
                'image' => null,
            ]);
        }

        // Keep a single default language.
        if (! Language::query()->where('is_default', Status::ENABLE)->exists()) {
            Language::query()->where('code', 'en')->update(['is_default' => Status::ENABLE]);
        }

        if (Schema::hasTable('general_settings') && Schema::hasColumn('general_settings', 'multi_language')) {
            DB::table('general_settings')->update(['multi_language' => Status::ENABLE]);
        }
    }

    public function down(): void
    {
        $codes = array_keys(config('toapp_languages.supported', []));
        Language::query()
            ->whereIn('code', $codes)
            ->where('code', '!=', 'en')
            ->delete();
    }
};
