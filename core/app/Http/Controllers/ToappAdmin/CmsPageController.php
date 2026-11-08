<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Models\CmsPage;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CmsPageController extends Controller
{
    public function editAbout()
    {
        $page = CmsPage::aboutUs();

        return view('toapp_admin.cms.about', [
            'pageTitle' => __('About Us'),
            'page' => $page,
        ]);
    }

    public function updateAbout(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'body' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $page = CmsPage::aboutUs();
        $page->title = $data['title'];
        $page->body = $this->sanitizeHtml($data['body'] ?? '');
        $page->is_published = (bool) ($data['is_published'] ?? false);
        $page->save();

        return back()->with('status', 'About Us content saved.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png', 'webp', 'gif']), 'max:5120'],
        ]);

        $dir = dirname(base_path()) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'cms';
        File::ensureDirectoryExists($dir);

        $file = $request->file('image');
        $name = Str::uuid()->toString() . '.' . strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $file->move($dir, $name);

        return response()->json([
            'url' => asset('assets/images/cms/' . $name),
        ]);
    }

    protected function sanitizeHtml(string $html): string
    {
        // Allow common rich-text tags from Quill; strip scripts/event handlers.
        $allowed = '<p><br><strong><b><em><i><u><s><a><ul><ol><li><h1><h2><h3><h4><blockquote><pre><span><img>';
        $clean = strip_tags($html, $allowed);
        $clean = preg_replace('/\son\w+\s*=\s*("|\').*?\1/iu', '', $clean) ?? $clean;
        $clean = preg_replace('/javascript\s*:/iu', '', $clean) ?? $clean;

        return $clean;
    }
}
