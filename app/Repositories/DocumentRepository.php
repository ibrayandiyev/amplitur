<?php

namespace App\Repositories;

use App\Enums\DocumentStatus;
use App\Models\Document;

class DocumentRepository extends Repository
{
    public function __construct(Document $model)
    {
        $this->model = $model;
    }

    /**
     * Accept a document
     *
     * @param   Document  $document  [$document description]
     *
     * @return  Document             [return description]
     */
    public function accept(Document $document): Document
    {
        $document->status = DocumentStatus::APPROVED;
        $document->save();

        return $document;
    }

    /**
     * Decline a document
     *
     * @param   Document  $document  [$document description]
     *
     * @return  Document             [return description]
     */
    public function decline(Document $document): Document
    {
        $document->status = DocumentStatus::DECLINED;
        $document->save();

        return $document;
    }

    /**
     * Put a document to analysis
     *
     * @param   Document  $document  [$document description]
     *
     * @return  Document             [return description]
     */
    public function inAnalysis(Document $document): Document
    {
        $document->status = DocumentStatus::IN_ANALYSIS;
        $document->save();

        return $document;
    }

    /**
     * Load a document 
     *
     * @param   Document  $document  [$document description]
     *
     * @return  Document             [return description]
     */
    public function show(Document $document)
    {
        $filepath   = storage_path('app') . "/".$document->filepath;
        $mime_type = mime_content_type($filepath);
        ;
        return response()->file($filepath);
    }
}