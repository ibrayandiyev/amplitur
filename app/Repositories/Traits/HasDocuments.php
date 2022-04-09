<?php

namespace App\Repositories\Traits;

use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

trait HasDocuments
{
    protected $uploadedDocuments;

    public function setUploadedDocuments($files, string $key = 'documents'): Repository
    {
        if (empty($files)) {
            return $this;
        }

        if (is_array($files)) {
            $this->uploadedDocuments = $files[$key];
        } else if ($files instanceof UploadedFile) {
            $this->uploadedDocuments[] = $files;
        }

        return $this;
    }

    public function handleUploadedDocuments(Model $resource)
    {
        if (is_null($this->uploadedDocuments)) {
            return;
        }

        if (is_array($this->uploadedDocuments)) {
            foreach ($this->uploadedDocuments as $uploadedDocument) {
                $filename = $uploadedDocument->getClientOriginalName();
                $filepath = $uploadedDocument->store('company-documents');

                $resource->documents()->create([
                    'provider_id' => $resource->provider->id,
                    'filename' => $filename,
                    'filepath' => $filepath,
                ]);
            }
        } else {
            $filename = $this->uploadedDocuments->getClientOriginalName();
            $filepath = $this->uploadedDocuments->store('company-documents');

            $resource->documents()->create([
                'provider_id' => $resource->provider->id,
                'filename' => $filename,
                'filepath' => $filepath,
            ]);
        }
    }
}