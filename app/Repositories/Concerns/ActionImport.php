<?php

namespace App\Repositories\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

trait ActionImport
{
    /**
     * Get import list of the resource
     *
     * @param  UploadedFile  $file
     * 
     * @return Collection
     */
    public function import(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        
        $filepath = $file->store('imports/events');
        $filepath = storage_path('app') . '/' . $filepath;

        switch ($extension) {
            case 'csv':
            case 'xlsx':
            case 'xls':
                return $this->importFile($filepath);
            break;

            default:
                throw new Exception("Invalid file");
            break;
        }
    }

    /**
     * Import an file to storage
     *
     * @param   string  $file
     *
     * @return  void
     */
    public function importFile($file)
    {
        try {
            Excel::queueImport(new $this->importClass, $file);
        } catch (Exception $ex) {
            bugtracker()->withPayload([
                'meta' => [
                    'file' => $file,
                    'import-class' => $this->importClass,
                ],
            ])->notifyException($ex);

            $this->imported();
            $this->importFail(true);
        }
    }
}
