<?php

namespace BigTB\SL\API\PDF\docs;

class PODDocGenerator extends DocGenerator
{

	/**
	 * Returns PDF bytes for the carrier POD.
	 * If the source file is already a PDF, returns it as-is.
	 * If it's an image, wraps it in a single-page PDF.
	 */
	public function generate(array $data): string
	{
		$podPath = $data['pod_path'] ?? null;

		$resolvedPath = $this->resolveFilePath($podPath);
		if (! $resolvedPath || ! file_exists($resolvedPath)) {
			throw new \RuntimeException('Carrier POD file not found on server.');
		}

		$mime = mime_content_type($resolvedPath);

		if ($mime === 'application/pdf') {
			return file_get_contents($resolvedPath);
		}

		return $this->wrapImageInPdf($resolvedPath);
	}

	protected function wrapImageInPdf(string $imagePath): string
	{
		$this->pdf->SetMargins(10, 10, 10);
		$this->pdf->SetAutoPageBreak(false);
		$this->pdf->AddPage();

		$pageW = $this->pdf->getPageWidth() - 20;
		$pageH = $this->pdf->getPageHeight() - 20;

		$this->pdf->Image($imagePath, 10, 10, $pageW, $pageH, '', '', '', true, 300, '', false, false, 0, 'CM');

		return $this->pdf->Output('carrier_pod.pdf', 'S');
	}

	protected function resolveFilePath(?string $path): ?string
	{
		if (! $path) {
			return null;
		}

		if (file_exists($path)) {
			return $path;
		}

		if (filter_var($path, FILTER_VALIDATE_URL) && function_exists('wp_upload_dir')) {
			$uploads = wp_upload_dir();
			$baseUrl = rtrim($uploads['baseurl'] ?? '', '/');
			$baseDir = rtrim($uploads['basedir'] ?? '', '/');
			if ($baseUrl && $baseDir && str_starts_with($path, $baseUrl)) {
				$relative = ltrim(substr($path, strlen($baseUrl)), '/');
				$mapped   = $baseDir . '/' . $relative;
				if (file_exists($mapped)) {
					return $mapped;
				}
			}
		}

		return null;
	}
}
