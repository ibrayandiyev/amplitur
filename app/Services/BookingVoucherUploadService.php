<?php

namespace App\Services;

use App\Models\BookingVoucherFile;
use App\Repositories\BookingVoucherFileRepository;
use App\Repositories\BookingVoucherRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookingVoucherUploadService
{
    /**
     * @var BookingVoucherRepository
     */
    protected $repository;

    /**
     * @var BookingVoucherRepository
     */
    protected $bookingVoucherFileRepository;

    public function __construct(BookingVoucherRepository $repository,
    BookingVoucherFileRepository $bookingVoucherFileRepository)
    {
        $this->repository                   = $repository;
        $this->bookingVoucherFileRepository = $bookingVoucherFileRepository;
    }

    /**
     * [upload description]
     *
     * @param   UploadedFile  $file
     * @param   BookingVoucherFile  $bookingVoucherFile
     *
     * @return  BookingVoucherFile|array|null
     */
    public function upload(UploadedFile $file, BookingVoucherFile $bookingVoucherFile)
    {
        return $this->uploadSingleFile($file, $bookingVoucherFile);
    }

    /**
     * [uploadSingleFile description]
     *
     * @param   UploadedFile            $file
     * @param   BookingVoucherFile      $bookingVoucherFile
     *
     * @return  BookingVoucherFile
     */
    protected function uploadSingleFile(UploadedFile $file, BookingVoucherFile $bookingVoucherFile): ?BookingVoucherFile
    {
        $filename = $file->getClientOriginalName();
        $filename = str_replace('.' . $file->getClientOriginalExtension(), '', $filename);
        $filename = Str::slug(uniqid() . '-' . $filename);
        $filename = $filename . '.' . $file->getClientOriginalExtension();
        $filename = Storage::disk('vouchers')->putFileAs(voucherPath() . 'vouchers', $file, $filename, 'public');
        $filename = str_replace(voucherPath(), '', $filename);

        $bookingVoucherFile = $this->bookingVoucherFileRepository->update($bookingVoucherFile, [
            'path' => 'vouchers',
            'filename' => str_replace('vouchers/', '', $filename),
            'title' => $file->getClientOriginalName(),
        ]);

        return $bookingVoucherFile;
    }
}
