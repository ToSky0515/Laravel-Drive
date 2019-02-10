<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Storage;
use App\File;
use App\Transformers\FileTransformer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class DeleteFileController extends ApiController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var File
     */
    private $file;

    /**
     * CopyEntriesController constructor.
     *
     * @param Request $request
     * @param File $file
     */
    public function __construct(Request $request, File $file)
    {
        parent::__construct();
        
        $this->request = $request;
        $this->file = $file;
    }

    /**
     * Make copies of all specified entries and their children.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete()
    {
        // TODO: maybe limit to 100 or so entries

        $this->validate($this->request, [
            'ids'=> 'required|array',
            'ids.*' => 'required|integer',
        ]);

        $fileIds = $this->request->get('ids');

        // TODO: use "show" policy method when it supports multiple file IDs
        //$this->authorize('index', [File::class, $fileIds]);

        $this->deleteEntries($fileIds);
        
        return $this->respondWithMessage("files deleted successfully.");
    }

    /**
     * @param array|\Illuminate\Support\Collection $fileIds
     * @return Collection
     */
    private function deleteEntries($fileIds)
    {
        foreach ($this->file->withTrashed()->whereIn('id', $fileIds)->cursor() as $file) {
            if ($file->type === 'folder') {
                $this->deleteFolderfile($file);
            } else {
                $this->deleteEntry($file);
            }
        }
    }

    /**
     * @param File $original
     * @return File
     */
    private function deleteEntry(File $original)
    {
        $action = $this->request->get('action');
        if ( $action === 'deleteforever' ) {
            $this->deleteFileStorate($original);
            $this->forceDeleteModel($original);
        } else if ( $action === 'restore') {
            $this->restoreModel($original);
        } else {
            $this->deleteModel($original);
        }
    }

    /**
     * @param File $original
     * @return File
     */
    private function deleteFolderfile(File $original)
    {
        $this->deleteChildEntries($original);
        $this->deleteEntry($original);

    }

    /**
     * @param File $original
     */
    private function deleteChildEntries(File $original)
    {
        $fileIds = $this->file->where('parent_id', $original->id)->pluck('id');
        if ( ! $fileIds->isEmpty()) {
            $this->deleteEntries($fileIds);
        }
    }

    /**
     * @param File $original
     * @return File
     */
    private function deleteModel(File $original)
    {
        return $original->delete();
    }

    /**
     * @param File $original
     * @param int|null $parentId
     * @return File
     */
    private function restoreModel(File $original)
    {
        // $original->history()->restore();
        return $original->restore();
    }

    /**
     * @param File $original
     * @param int|null $parentId
     * @return File
     */
    private function forceDeleteModel(File $original)
    {
        // $original->history()->forceDelete();
        return $original->forceDelete();
    }



    /**
     * @param File $original
     */
    private function deleteFileStorate(File $original)
    {
        Storage::disk('uploads_local')->deleteDirectory($original->file_name);
    }
}
