<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

abstract class PdfReportService
{
    /**
     * Generate a PDF for the specified resource
     *
     * @param int|null $id Single record ID, null for all records
     * @param array $filters Optional filters for the report
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generatePdf(?int $id = null, array $filters = [])
    {
        $data = $this->getData($id, $filters);
        $viewName = $this->getViewName();

        return PDF::loadView($viewName, $data);
    }

    /**
     * Download the PDF with a specified filename
     *
     * @param int|null $id Single record ID, null for all records
     * @param array $filters Optional filters for the report
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(?int $id = null, array $filters = [])
    {
        $pdf = $this->generatePdf($id, $filters);
        return $pdf->download($this->getFileName($id) . '.pdf');
    }

    /**
     * Stream the PDF directly to the browser for viewing/printing
     *
     * @param int|null $id Single record ID, null for all records
     * @param array $filters Optional filters for the report
     * @return \Illuminate\Http\Response
     */
    public function streamPdf(?int $id = null, array $filters = [])
    {
        $pdf = $this->generatePdf($id, $filters);
        return $pdf->stream($this->getFileName($id) . '.pdf');
    }

    /**
     * Get the data needed for the report
     *
     * @param int|null $id Single record ID, null for all records
     * @param array $filters Optional filters for the report
     * @return array
     */
    abstract protected function getData(?int $id = null, array $filters = []): array;

    /**
     * Get the blade view name for the report template
     *
     * @return string
     */
    abstract protected function getViewName(): string;

    /**
     * Get the filename for the PDF
     *
     * @param int|null $id Single record ID, null for all records
     * @return string
     */
    abstract protected function getFileName(?int $id = null): string;
}
