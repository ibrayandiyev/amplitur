<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Document;
use App\Models\Provider;
use App\Repositories\DocumentRepository;
use Exception;
use Illuminate\Http\Request;

class CompanyDocumentController extends Controller
{
    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * Accept a document
     *
     * @param   Request   $request
     * @param   Provider  $provider
     * @param   Company   $company
     * @param   Document  $document
     *
     * @return  Illuminate\Http\Response
     */
    public function accept(Request $request, Provider $provider, Company $company, Document $document)
    {
        try {
            $this->documentRepository->accept($document);

            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withSuccess(__('resources.companies.document.accepted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * Decline a document
     *
     * @param   Request   $request
     * @param   Provider  $provider
     * @param   Company   $company
     * @param   Document  $document
     *
     * @return  Illuminate\Http\Response
     */
    public function decline(Request $request, Provider $provider, Company $company, Document $document)
    {
        try {
            $this->documentRepository->decline($document);

            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withSuccess(__('resources.companies.document.declined'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * Put a document to analysis
     *
     * @param   Request   $request
     * @param   Provider  $provider
     * @param   Company   $company
     * @param   Document  $document
     *
     * @return  Illuminate\Http\Response
     */
    public function inAnalysis(Request $request, Provider $provider, Company $company, Document $document)
    {
        try {
            $this->documentRepository->inAnalysis($document);

            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withSuccess(__('resources.companies.document.moved-analysis'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * Destroy a document
     *
     * @param   Request   $request
     * @param   Provider  $provider
     * @param   Company   $company
     * @param   Document  $document
     *
     * @return  Illuminate\Http\Response
     */
    public function destroy(Request $request, Provider $provider, Company $company, Document $document)
    {
        try {
            $this->documentRepository->delete($document);

            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withSuccess(__('resources.documents.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * View a document
     *
     * @param   Request   $request
     * @param   Provider  $provider
     * @param   Company   $company
     * @param   Document  $document
     *
     * @return  Illuminate\Http\Response
     */
    public function show(Request $request, Provider $provider, Company $company, Document $document)
    {
        try {
            if (!user()->canSeeCompanyDocument($document)) {
                forbidden();
            }
            return $this->documentRepository->show($document);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withError($ex->getMessage());
        }
    }
}
