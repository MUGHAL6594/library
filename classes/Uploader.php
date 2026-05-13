<?php

class Uploader {
  private $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
  private $allowedMimes      = ["image/jpeg", "image/png", "image/gif"];
  private $magicBytes        = [
    "jpg"  => "\xFF\xD8\xFF",
    "jpeg" => "\xFF\xD8\xFF",
    "png"  => "\x89\x50\x4E\x47",
    "gif"  => "GIF8",
  ];
  private $maxSize    = 2097152; // 2MB
  private $uploadPath = "uploads/";

  public function upload($file) {
    // 1. Check for basic PHP upload errors
    if ($file["error"] !== UPLOAD_ERR_OK) {
      return ["error" => "File upload failed with error code: " . $file["error"]];
    }

    $fileName = $file["name"];
    $fileSize = $file["size"];
    $fileTmp  = $file["tmp_name"];
    $fileMime = $file["type"];

    // 2. Validate Extension
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($ext, $this->allowedExtensions)) {
      return ["error" => "Invalid file extension. Only JPG, PNG, and GIF allowed."];
    }

    // 3. Validate MIME Type
    if (!in_array($fileMime, $this->allowedMimes)) {
      return ["error" => "Invalid MIME type. The file does not appear to be a real image."];
    }

    // 4. Validate Size
    if ($fileSize > $this->maxSize) {
      return ["error" => "File is too large. Maximum size is 2MB."];
    }

    // 5. Validate Magic Bytes (Deep Content Inspection)
    $handle     = fopen($fileTmp, "rb");
    $firstBytes = fread($handle, 4);
    fclose($handle);

    $magicMatch = false;
    foreach ($this->magicBytes as $type => $magic) {
      if (strncmp($firstBytes, $magic, strlen($magic)) === 0) {
        $magicMatch = true;
        break;
      }
    }

    if (!$magicMatch) {
      return ["error" => "Security Alert: File content does not match its extension."];
    }

    // 6. Ensure Directory Exists and is Writable
    if (!is_dir($this->uploadPath)) {
      if (!mkdir($this->uploadPath, 0755, true)) {
        return ["error" => "Server Error: Failed to create upload directory."];
      }
    }

    if (!is_writable($this->uploadPath)) {
        return ["error" => "Server Error: Upload directory is not writable."];
    }

    // 7. Save with Unique Filename
    $newFileName = uniqid("cover_", true) . "." . $ext;
    $destination = $this->uploadPath . $newFileName;

    if (move_uploaded_file($fileTmp, $destination)) {
      return ["success" => true, "file" => $newFileName];
    } else {
      return ["error" => "Server Error: Could not move uploaded file."];
    }
  }
}
