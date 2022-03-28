<?php

namespace App\Http\Controllers\Challenge;

use App\Http\Controllers\Controller;
use App\Http\Services\ChallengeFormatService;
use App\Http\Services\FileUploadRemoveService;
use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    public function index()
    {
        $challenges = Challenge::query()->with('category')->get();

        return response()->json($challenges);
    }

    public function show(Challenge $challenge)
    {
        return response()->json($challenge->load('category'));
    }

    public function store(Challenge $challenge, Request $request)
    {
        try {
            $data = $request->except('image');

            $challenge->fill($data)->save();

            if ($request->get('image') &&
                strpos($request->get('image'), 'image') !== false &&
                strpos($request->get('image'), 'base64') !== false) {
                $image_path = FileUploadRemoveService::fileUpload('challenge', $request->get('image'), 'image');
                $challenge->image = $image_path;
                $challenge->update(['image' => $image_path]);
            }

            return response()->json($challenge);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function update(Challenge $challenge, Request $request)
    {
        try {
            $data = $request->except('image');
            if ($request->get('image') && $challenge->getOriginal('image') !== $request->get('image')) {
                FileUploadRemoveService::removeFile($challenge->image);
                $data['image'] = FileUploadRemoveService::fileUpload('challenge', $request->get('image'), 'image');
            }

            $challenge->update($data);

            return response()->json($data);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }

    }

    public function destroy(Challenge $challenge)
    {
        try {
            $challenge->delete();
            return response()->json(['message' => 'Deleted Successfully']);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function userChallenges()
    {
        $challenges = ChallengeFormatService::formatChallenges();
        return response()->json($challenges);
    }


}
