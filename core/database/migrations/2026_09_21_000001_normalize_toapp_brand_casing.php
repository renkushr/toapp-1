<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $replace = static function (?string $value): ?string {
            if ($value === null || $value === '') {
                return $value;
            }

            return str_replace(['To-app', 'TO-APP'], 'To-App', $value);
        };

        if (Schema::hasTable('general_settings')) {
            $row = DB::table('general_settings')->orderBy('id')->first();
            if ($row) {
                $updates = [];
                foreach (['site_name', 'email_from_name', 'sitename'] as $column) {
                    if (Schema::hasColumn('general_settings', $column) && property_exists($row, $column)) {
                        $current = (string) $row->{$column};
                        $next = $replace($current);
                        if ($next !== $current) {
                            $updates[$column] = $next;
                        }
                    }
                }
                if ($updates !== []) {
                    DB::table('general_settings')->where('id', $row->id)->update($updates);
                }
            }
        }

        if (Schema::hasTable('cms_pages')) {
            $pages = DB::table('cms_pages')->orderBy('id')->get(['id', 'title', 'body']);
            foreach ($pages as $page) {
                $updates = [];
                foreach (['title', 'body'] as $column) {
                    $current = (string) ($page->{$column} ?? '');
                    $next = $replace($current);
                    if ($next !== $current) {
                        $updates[$column] = $next;
                    }
                }
                if ($updates !== []) {
                    DB::table('cms_pages')->where('id', $page->id)->update($updates);
                }
            }
        }

        if (Schema::hasTable('pages')) {
            $pages = DB::table('pages')->orderBy('id')->get();
            foreach ($pages as $page) {
                $updates = [];
                foreach (['title', 'description', 'content', 'name'] as $column) {
                    if (! property_exists($page, $column)) {
                        continue;
                    }
                    $current = (string) ($page->{$column} ?? '');
                    $next = $replace($current);
                    if ($next !== $current) {
                        $updates[$column] = $next;
                    }
                }
                if ($updates !== []) {
                    DB::table('pages')->where('id', $page->id)->update($updates);
                }
            }
        }

        if (Schema::hasTable('frontends')) {
            $rows = DB::table('frontends')->orderBy('id')->get(['id', 'data_values']);
            foreach ($rows as $row) {
                $raw = (string) ($row->data_values ?? '');
                if ($raw === '' || (stripos($raw, 'To-app') === false && strpos($raw, 'TO-APP') === false)) {
                    continue;
                }
                $next = $replace($raw);
                if ($next !== $raw) {
                    DB::table('frontends')->where('id', $row->id)->update(['data_values' => $next]);
                }
            }
        }
    }

    public function down(): void
    {
        // Irreversible brand casing normalization.
    }
};
